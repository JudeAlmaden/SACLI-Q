<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
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
use App\Events\CallingTicket; // Assuming you have this event for broadcasting ticket calls
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{
    
    //Public Methods
    public function liveQueue($code)
    {
        try {
            $queue = Queue::with('Windows')->where('code', $code)->firstOrFail();

            // Pass the fetched data to the view
            return view('public.LiveQueue', compact('queue'));

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No queue found with the given code.',
            ], 404);
        }
    }
    

    function ticketing($code){
        try {
            $queue = Queue::where('code', $code)->firstOrFail();
            return view('public.Ticketing',compact('queue'));

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No queue found with the given code.',
            ], 404);
        }
    }


    public function ticketingSubmit(Request $request)
    {
        // Validate the request data
        $request->validate([
            'queue_id' => 'required|exists:queues,id',
            'window_id' => 'required|exists:windows,id',
            'name' => 'nullable|string|max:255',
        ], [
            'window_id.required' => 'Please select what window to queue',
            'window_id.exists' => 'The selected window group does not exist',
        ]);
    
        try {
            // Fetch the queue and window
            $window = Window::where('id', $request->window_id)
                ->where('queue_id', $request->queue_id)
                ->firstOrFail();
    
    
            // Get the number of tickets generated today for the specific window
            $ticketsGeneratedToday = Ticket::where('window_id', $request->window_id)
                ->whereDate('created_at', Carbon::today())
                ->count();

            // Check if the ticket limit has been reached
            if ($ticketsGeneratedToday >= $window->limit) {
                // If the limit is reached, close the window and return an error
                $window->status = 'closed';
                $window->save();
    
                return back()->withErrors(['error' => 'The ticket limit for today has been reached. The window has been closed.']);
            }
    
            // Generate a unique 6-character code
            // Get the largest ticket code for this queue today
            $latestTicket = Ticket::where('queue_id', $request->queue_id)
                ->whereDate('created_at', Carbon::today())
                ->orderByDesc('created_at')
                ->first();

            if ($latestTicket && is_numeric($latestTicket->code)) {
                $code = str_pad((int)$latestTicket->code + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $code = '0001';
            }
            
            // Get the next ticket number for the window and increment it to 1 and it will use it for the ticket number here
            $ticket_number = Ticket::where('window_id', $request->window_id)
                      ->whereDate('created_at', Carbon::today())
                      ->max('ticket_number');
            $ticket_number = $ticket_number ? $ticket_number + 1 : 1;
    
            // Create a new Ticket record
            $Ticket = Ticket::create([
                'queue_id' => $request->queue_id,
                'ticket_number' => $ticket_number,
                'window_id' => $request->window_id,
                'name' => $request->name,
                'status' => "Waiting",
                'code' => $code,
            ]);
    
            // Broadcast the new ticket event
            broadcast(new NewTicketEvent($Ticket->queue_id));
    
            return redirect()->route('ticketing.success', ['id' => $Ticket->id]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while creating the ticket.']);
        }
    }
    
    public function ticketingSuccess($id)
    {
        $Ticket = Ticket::with('window')->whereDate('created_at', Carbon::today())->findOrFail($id);
        $Queue = Queue::findOrFail($Ticket->queue_id);
        return view('public.TicketReceipt', compact('Ticket', 'Queue'));
    }


    public function info(Request $request)
    {
        $ticketCode = $request->input('ticketCode'); // Get the ticket code from the request
        $queue_id = $request->input('queue_id'); // Get the queue ID from the request


        $allQueues = Queue::all(); // Get all queues for the dropdown

        // Search for the ticket by code and where status is not "Completed"
        $ticket = Ticket::with('queue')
            ->with('window')
            ->where('code', $ticketCode)
            ->where('status', '!=', 'Completed')
            ->where('queue_id', $queue_id)
            ->whereDate('created_at', Carbon::today()) 
            ->first();
        
        if (!$ticket) {
            // If no ticket is found, return with an error message
            $message = 'Ticket not found.';
            return view('public.info', compact('message', 'allQueues'));
        }
    
        // Initialize ticket position
        $ticketPosition = null;
    
        // Check if the ticket status is 'Waiting'
        if ($ticket->status == 'Waiting') {
            // Get the position of the ticket in the queue based on its 'created_at'
            $ticketPosition = Ticket::where('queue_id', $ticket->queue_id)
                                    ->where('status', 'Waiting')
                                    ->whereDate('created_at', Carbon::today())
                                    ->orderBy('created_at', 'asc')  // Oldest ticket is first
                                    ->pluck('id'); // Get list of ticket IDs in order of waiting
    
            // Find the position of the current ticket in the ordered list
            $ticketPosition = $ticketPosition->search($ticket->id) + 1; // Position is 1-based index
        }
    
        // Pass the ticket and position to the view
        return view('public.info', compact('ticket', 'ticketPosition', 'allQueues'));
    }
    
    public function broadcastEventCallTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'queue_id' => 'required',
            'ticket_number' => 'required',
            'window_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Invalid request data.',
            ], 422);
        }

        // Store values
        $queue_id = $request->queue_id;
        $ticketNumber = $request->ticket_number;
        $windowName = $request->window_name;

        // Broadcast the event
        broadcast(new CallingTicket($queue_id, $ticketNumber, $windowName));

        // Return the broadcasted data as part of the success response
        return response()->json([
            'success' => true,
            'message' => 'Event broadcasted',
            'data' => [
                'ticketNumber' => $ticketNumber,
                'windowName' => $windowName,
                'queueCode' => $queue_id,
            ]
        ]);
    }


}
