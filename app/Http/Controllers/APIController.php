<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Queue;
use App\Models\Ticket;
use App\Models\Window; 
use App\Models\WindowAccess; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Events\DashboardEvent;
use App\Events\NewTicketEvent;

class APIController extends Controller
{
    //Fetch api methods
    //For setting window name
    public function getLiveData($id)
    {
        $queue = Queue::with('Windows')->findOrFail($id);
    
        // Get people who are Ticket on each Window with the status of "Calling"
        $windows = $queue->Windows->map(function ($Window) {
            $Window->issuedTickets = Ticket::where('window_id', $Window->id)
                ->where('status', 'Calling')
                ->whereDate('created_at', Carbon::today()) 
                ->with('user')
                ->get();
    
            // Join with window_access to get window_name
            $WindowAccess = WindowAccess::where('window_id', $Window->id)
                ->first();
            $Window->window_name = $WindowAccess ? $WindowAccess->window_name : null;
    
            return $Window;
        });
    
        // Get 7 oldest Ticket records with status "Waiting"
        $ticketsOldest = Ticket::where('queue_id', $id)
            ->where('status', 'Waiting')
            ->orderBy('created_at', 'asc')
            ->take(8)
            ->with('user') 
            ->get();
    
        return response()->json([
            'queue' => $queue,
            'windows' => $windows,
            'ticketsOldest' => $ticketsOldest,
        ]);
    }

    public function updateWindow(Request $request, $id){
        $request->validate([
            'window_name' => 'required|string|max:255',
        ]);

        $WindowId = $id;
        $user_id = Auth::user()->id;

        $window = WindowAccess::where('user_id', $user_id)
            ->where('window_id', $WindowId)
            ->first();

        if ($window === null) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this window.',
            ], 403);
        }

        $window->window_name = $request->window_name;
        $window->save();

        return response()->json([
            'success' => true,
            'message' => 'Window Name Updated',
        ], 200);
    }

    //Setting window ticket generation limit
    public function setLimit($window_id, $limit)
    {
        // Find the window by its ID
        $window = Window::find($window_id);
        $user_id = Auth::user()->id;
        
        // Check if the user has privileges to toggle the window
        $accessExists = WindowAccess::where('user_id', $user_id)
                                     ->where('queue_id', $window->queue_id)
                                     ->where(function ($query) {
                                         $query->where('can_change_ticket_limit', true);
                                     })
                                     ->exists();
        if (!$accessExists) {
            return response()->json(['status' => 'error', 'message' => 'You do not have permission to change this']);
        }

        // Validate limit value (make sure it's an integer and within a valid range)
        if (!is_numeric($limit) || $limit <= 0) {
            return response()->json(['status' => 'error', 'message' => 'Invalid limit value.'], 400);
        }

        if (!$window) {
            // If the window doesn't exist, return a 404 response
            return response()->json(['status' => 'error', 'message' => 'Window not found.'], 404);
        }

        // Update the window's limit
        $window->limit = $limit;
        $window->save();

        // Return a success response
        return response()->json(['status' => 'success', 'message' => 'Limit updated successfully.'], 200);
    }




    //Get Current Ticket For Window
    public function getCurrentTicketForWindow($WindowId){
       $user_id = Auth::user()->id;
       $onCall = Ticket::where('window_id', $WindowId)
           ->where('handled_by', $user_id)
           ->where('status', 'Calling')
           ->whereDate('created_at', Carbon::today()) 
           ->first();

       if ($onCall) {
           return response()->json([
               'success' => true,
               'ticket_number' => $onCall->code,
               'name' => $onCall->name
           ]);
       } else {
           return response()->json([
               'success' => false,
               'message' => 'No tickets are currently being called.'
           ]);
       }
    }

    //For button "Next Ticket"
    public function getNextTicketForWindow($WindowId){
        //Check if user is authenticated
        $user_id = Auth::user()->id;

        //Number of On Called by Window
        $onCalled = Ticket::where('window_id', $WindowId)
                        ->where('handled_by', $user_id)
                        ->where('status', 'Calling')
                        ->whereDate('created_at', Carbon::today()) 
                        ->count();

        if($onCalled > 0){
            return response()->json([
                'success'=>false, 
                'message' => 'Please finish the current ticket first.'], 200);
        }

        //Handles a ticket  the next ticket for the given window 
        $Ticket = Ticket::where('window_id', $WindowId)
                        ->where('status', 'Waiting')
                        ->whereDate('created_at', Carbon::today()) 
                        ->orderBy('created_at', 'asc')
                        ->first();

        //Check is user has access to the window
        $userHasAccess = WindowAccess::where('user_id', $user_id)
            ->where('window_id', $WindowId)->first();

        if (!$userHasAccess || $userHasAccess == null) {
            return response()->json([
                'success'=>false, 
                'message' => 'You do not have access to this window.'
            ], 200);

        }else if($userHasAccess->window_name == null && $userHasAccess->window_name == ''){
            return response()->json([
                'success'=>false, 
                'message' => 'Please Enter your window name first'
            ], 200);

        }else if($Ticket == null){
            return response()->json([
                'success'=>false, 
                'message' => 'No more tickets to call.'
            ], 200);
        }
        else{
            //Update Ticket Status
            $Ticket->status = 'Calling';
            $Ticket->handled_by = $user_id;
            $Ticket->called_at = Carbon::now();
            $Ticket->save();

            broadcast(new DashboardEvent($Ticket->queue_id));

            return response()->json([
                'success'=>true, 
                'message'=> "Successfully called the next ticket",
                'data' =>$Ticket
            ], 200);
        }
        
    }

   //Set the completed status of the ticket
   public function setToComplete($WindowId)
    {
        $user_id = Auth::id();
        $Ticket = Ticket::where('window_id', $WindowId)
                        ->where('handled_by', $user_id)
                        ->where('status', 'Calling')
                        ->whereDate('created_at', Carbon::today()) 
                        ->first();

        if ($Ticket) {
            $Ticket->status = 'Completed';
            $Ticket->completed_at = Carbon::now();
            $Ticket->save();

            broadcast(new DashboardEvent($Ticket->queue_id));

            return response()->json(['success' => true, 'message' => 'Ticket marked as completed.']);
        } else {
            return response()->json(['success' => false, 'message' => 'No ticket found to complete.']);
        }
    }

    //Get the next ticket that is currently On-hold
    public function getNextOnHoldTicket($WindowId){
        //Check if user is authenticated
        $user_id = Auth::user()->id;

        //Ensure that the user is not handling a ticket currently 
        $onCall = Ticket::where('window_id', $WindowId)
                        ->where('handled_by', $user_id)
                        ->where('status', 'Calling')
                        ->whereDate('created_at', Carbon::today()) 
                        ->count();

        if($onCall > 0){
            return response()->json([
                'success'=>false, 
                'message' => 'Please finish the current ticket first.'], 200);
        }

        //Gets the next ticket for the given window group by status of On Hold
        $Ticket = Ticket::where('window_id', $WindowId)
                        ->where('status', 'On Hold')
                        ->whereDate('created_at', Carbon::today()) 
                        ->orderBy('created_at', 'asc')
                        ->first();

        //Checks if is user has access to the window
        $userHasAccess = WindowAccess::where('user_id', $user_id)
            ->where('window_id', $WindowId)->first();

        if (!$userHasAccess || $userHasAccess == null) {
            return response()->json([
                'success'=>false, 
                'message' => 'You do not have access to this window.
            '], 200);

        }else if($userHasAccess->window_name == null && $userHasAccess->window_name == ''){
            return response()->json([
                'success'=>false, 
                'message' => 'Please Enter your window name first'
            ], 200);

        }else if($Ticket == null){
            return response()->json([
                'success'=>false, 
                'message' => 'No more tickets to call.'
            ], 200);
        }
        else{
            //Update Ticket Status
            $Ticket->status = 'Calling';
            $Ticket->handled_by = $user_id;
            $Ticket->save();

            broadcast(new DashboardEvent($Ticket->queue_id));

            return response()->json([
                'success'=>true, 
                'message'=> "Successfully called the next ticket",
                'data' =>$Ticket
            ], 200);
        }
        
    }

    //Set the ticket to On-hold status (Person was called but was absent)
    public function putTicketOnHold($WindowId){
        $user_id = Auth::id();
        $Ticket = Ticket::where('window_id', $WindowId)
                        ->where('handled_by', $user_id)
                        ->where('status', 'Calling')
                        ->whereDate('created_at', Carbon::today()) 
                        ->first();

        if ($Ticket) {
            $Ticket->status = 'On Hold';
            $Ticket->save();

            broadcast(new DashboardEvent($Ticket->queue_id));

            return response()->json(['success' => true, 'message' => 'Ticket put on hold.']);
        } else {
            return response()->json(['success' => false, 'message' => 'No ticket found to put on hold.']);
        }
    }

    //get data for a user window such as number of upcoming tickets
    public function getWindowUserData($WindowId, $userId){
        $upcomingTicketsCount = Ticket::where('window_id', $WindowId)
                                        ->where('status', 'Waiting')
                                        ->whereDate('created_at', Carbon::today()) 
                                        ->count();
        $completedTickets = Ticket::where('window_id', $WindowId)
                                        ->where('handled_by', $userId)
                                        ->where('status', 'Completed')
                                        ->whereDate('created_at', Carbon::today()) 
                                        ->count();
        return response()->json(['success' => true, 'upcoming_tickets_count' => $upcomingTicketsCount, 'completed_tickets_count' => $completedTickets]);
    }


    public function handleTicket($WindowId,$TicketId){
        //Check if user is authenticated
        $user_id = Auth::user()->id;

        //Number of On Called by Window
        $onCalled = Ticket::where('window_id', $WindowId)
                        ->where('handled_by', $user_id)
                        ->where('status', 'Calling')
                        ->whereDate('created_at', Carbon::today()) 
                        ->count();

        if($onCalled > 0){
            return response()->json([
                'success'=>false, 
                'message' => 'Please finish the current ticket first.'], 200);
        }

        // Get the ticket for the given window based on Ticket id and ensure that the status is 'On Hold' or 'Waiting'
        $Ticket = Ticket::where('window_id', $WindowId)
                        ->where('id', $TicketId)
                        ->whereDate('created_at', Carbon::today()) 
                        ->whereIn('status', ['On Hold', 'Waiting'])
                        ->first(); 
                        
        //Check is user has access to the window
        $userHasAccess = WindowAccess::where('user_id', $user_id)
            ->where('window_id', $WindowId)->first();

        //Error Checking
        if (!$userHasAccess || $userHasAccess == null) {
            return response()->json([
                'success'=>false, 
                'message' => 'You do not have access to this window.
            '], 200);

        }else if($userHasAccess->window_name == null && $userHasAccess->window_name == ''){
            return response()->json([
                'success'=>false, 
                'message' => 'Please Enter your window name first'
            ], 200);

        }else if($Ticket == null){
            return response()->json([
                'success'=>false, 
                'message' => 'The ticket is no longer available.'
            ], 200);
        }
        else{
            //Update Ticket Status
            $Ticket->status = 'Calling';
            $Ticket->handled_by = $user_id;
            $Ticket->called_at = Carbon::now();
            $Ticket->save();

            broadcast(new DashboardEvent($Ticket->queue_id));

            return response()->json([
                'success'=>true, 
                'message'=> "Successfully called the next ticket",
                'data' =>$Ticket
            ], 200);
        }
    }

    // Get all On-Hold tickets with pagination
    public function getTicketsOnHold($WindowId, Request $request) {    
        $search = $request->input('search', '');  // Get search term
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 20);
    
        $query = Ticket::where('window_id', $WindowId)
                        ->where('status', 'On Hold')
                        ->whereDate('created_at', Carbon::today()) ;
    
        // Apply search if query exists
        if ($search) {
            $query->where('code', 'like', '%' . $search . '%');  // You can adjust this field and condition based on your ticket structure
        }
    
        $tickets = $query->paginate($perPage, ['*'], 'page', $page);
    
        return response()->json([
            'success' => true,
            'tickets' => $tickets->items(),
            'total_pages' => $tickets->lastPage(),
        ]);
    }

    public function getUpcomingTickets($WindowId, Request $request) {
        // Get the per_page, sort_by, and sort_order parameters
        $perPage = $request->get('per_page', 20);
        $sortBy = $request->get('sort_by', 'completed_at'); // Default to 'completed_at'
        $sortOrder = $request->get('sort_order', 'desc'); // Default to descending order
    
        // Get tickets, paginate, and apply dynamic sorting
        $tickets = Ticket::where('window_id', $WindowId)
                         ->where('status', 'Waiting')
                         ->whereDate('created_at', Carbon::today()) 
                         ->orderBy($sortBy, $sortOrder) // Apply dynamic sorting
                         ->paginate($perPage);
    
        return response()->json([
            'success' => true,
            'tickets' => $tickets->items(),
            'total_pages' => $tickets->lastPage(),
            'current_page' => $tickets->currentPage(),
        ]);
    }

    // Get all Completed tickets with pagination
    public function getCompletedTickets($WindowId, Request $request) {
        // Get the per_page, sort_by, and sort_order parameters
        $perPage = $request->get('per_page', 20);
        $sortBy = $request->get('sort_by', 'completed_at'); // Default to 'completed_at'
        $sortOrder = $request->get('sort_order', 'desc'); // Default to descending order
    
        // Get tickets, paginate, and apply dynamic sorting
        $tickets = Ticket::where('window_id', $WindowId)
                         ->where('status', 'Completed')
                         ->whereDate('created_at', Carbon::today()) 
                         ->orderBy($sortBy, $sortOrder) // Apply dynamic sorting
                         ->paginate($perPage);
    
        return response()->json([
            'success' => true,
            'tickets' => $tickets->items(),
            'total_pages' => $tickets->lastPage(),
            'current_page' => $tickets->currentPage(),
        ]);
    }
    

}
