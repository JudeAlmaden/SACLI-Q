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
        $request->validate([
            'queue_id'  => 'required|exists:queues,id',
            'window_id' => 'required|exists:windows,id',
            'name'      => 'nullable|string|max:255',
        ]);

        try {
            $window = Window::where('id', $request->window_id)
                ->where('queue_id', $request->queue_id)
                ->firstOrFail();

            // Close window if limit reached or closed
            $todayCount = Ticket::where('window_id', $window->id)
                ->whereDate('created_at', today())
                ->count();

            if ($todayCount >= $window->limit || $window->status === 'closed') {
                $window->update(['status' => 'closed']);
                return back()->withErrors(['error' => 'Window is closed or ticket limit reached.']);
            }

            /* =========================
            DETERMINE WINDOW PREFIX (QUEUE-WIDE)
            ========================== */
            $letter = strtoupper(substr($window->name, 0, 1));

            // Get all windows in this queue with the same first letter, sorted by ID
            $windowsWithSameLetter = Window::where('queue_id', $window->queue_id)
                ->whereRaw('UPPER(LEFT(name, 1)) = ?', [$letter])
                ->orderBy('id')
                ->pluck('id')
                ->toArray();

            // Determine position of this window in that list
            $position = array_search($window->id, $windowsWithSameLetter);
            $prefix = $letter;
            if ($position !== false && $position > 0) {
                $prefix .= $position + 1; // C2, C3, etc.
            }

            /* =========================
            TICKET NUMBER
            ========================== */
            $ticketNumber = Ticket::where('window_id', $window->id)
                ->whereDate('created_at', today())
                ->max('ticket_number');

            $ticketNumber = $ticketNumber ? $ticketNumber + 1 : 1;

            $code = $prefix . str_pad($ticketNumber, 3, '0', STR_PAD_LEFT);

            /* =========================
            CREATE TICKET
            ========================== */
            $ticket = Ticket::create([
                'queue_id'       => $request->queue_id,
                'window_id'      => $window->id,
                'ticket_number'  => $ticketNumber,
                'name'           => $request->name,
                'status'         => 'Waiting',
                'code'           => $code,
            ]);

            broadcast(new NewTicketEvent($ticket->queue_id));

            return redirect()->route('ticketing.success', $ticket->id);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create ticket.']);
        }
    }

    public function ticketingSuccess($id)
    {
        $Ticket = Ticket::with('window')->whereDate('created_at', Carbon::today())->findOrFail($id);
        $Queue = Queue::findOrFail($Ticket->queue_id);        
        $Position = Ticket::where('window_id', $Ticket->window_id)
            ->where('status', 'Waiting')
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'asc')
            ->pluck('id');
            $Position = $Position->search($Ticket->id) + 1;

        return view('public.TicketReceipt', compact('Ticket', 'Queue', 'Position'));
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
            $ticketPosition = Ticket::where('window_id', $Ticket->window_id)
            ->where('status', 'Waiting')
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'asc')
            ->pluck('id');
            $Position = $Position->search($Ticket->id) + 1;
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
        broadcast(new CallingTicket($queue_id, $ticketNumber,$windowName));

        // Return the broadcasted data as part of the success response
        return response()->json([
            'success' => true,
            'message' => 'Event broadcasted',
            'data' => [
                'ticketNumber' => $ticketNumber,
                'queueCode' => $queue_id,
            ]
        ]);
    }


}
