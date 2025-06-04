<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Events\QueueSettingsChanged;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Queue;
use App\Models\Ticket;
use App\Models\Window; 
use App\Models\WindowAccess; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class QueueController extends Controller
{
    // Managing Queues
    // definition of ('admin.queue.list)
    public function adminQueues(Request $request)
    {
        $query = Queue::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $queues = $query->with('Windows')->paginate(10);
        $Windows = Window::all();

        return view('admin.queues', compact('queues', 'Windows'));
    }

    //Create and Delete a queue a new queue and redirect to route('admin.queue.list')
    public function createQueue(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:queues,name',
            'window_groups' => 'array',
            'window_groups.*.name' => 'string',
            'window_groups.*.description' => 'string',
        ]);

        // Double-check uniqueness in case of race condition
        if (Queue::where('name', $request->name)->exists()) {
            return redirect()->back()->withErrors(['name' => 'Queue name must be unique.'])->withInput();
        }

        // Generate a unique 6-character code
        $uniqueCode = $this->generateUniqueCode();

        // Create and save the queue
        $queue = new Queue();
        $queue->name = $request->name;
        $queue->code = $uniqueCode; 
        $queue->save();

        // Save associated window groups if provided
        if ($request->window_groups != null) {
            foreach ($request->window_groups as $WindowData) {
                $Window = new Window($WindowData);
                $queue->Windows()->save($Window);
            }
        }

        return redirect()->route('admin.queue.list')->with('success', 'Queue created successfully.');
    }
    
    public function deleteQueue($id)
    {
        $queue = Queue::findOrFail($id);
        $queue->delete();

        return redirect()->route('admin.queue.list')->with('success', 'Queue deleted successfully.');
    }

    //To see associated queue windows and other data //Admin Sectionpublic function viewQueue($id)
    public function viewQueue($id)
    {
        // Load queue with related windows, relation method should be named `windows` in your model
        $queue = Queue::with('windows')->findOrFail($id);

        // Get all window IDs for this queue
        $windowIds = $queue->windows->pluck('id');

        // Get unique user IDs who have access to these windows
        $uniqueUserIds = WindowAccess::whereIn('window_id', $windowIds)->pluck('user_id')->unique();

        // Fetch users and access list
        $uniqueUsers = User::whereIn('id', $uniqueUserIds)->get();
        $accessList = WindowAccess::whereIn('window_id', $windowIds)->get();

        // User windows grouped by user_id with eager loading of window relation (relation should be `window`)
        $userWindows = WindowAccess::with('window')->whereIn('user_id', $uniqueUserIds)->get()->groupBy('user_id');

        // Get tickets for this queue
        $tickets = Ticket::where('queue_id', $queue->id)->get();

        $totalTickets = $tickets->count();
        $servedTickets = $tickets->whereNotNull('completed_at');
        $servedCount = $servedTickets->count();

        // Average Queue Time in seconds
    $averageQueueTime = $servedTickets->map(function ($ticket) {
        $completedAt = \Carbon\Carbon::parse($ticket->completed_at);
        $createdAt = \Carbon\Carbon::parse($ticket->created_at);
        return $completedAt->timestamp - $createdAt->timestamp;
    })->filter()->avg();


        // Tickets count per user (group by handled_by)
        $ticketsPerUser = $servedTickets->groupBy('handled_by')->map->count();

        // Date-based analytics
        $today = now()->startOfDay();
        $ticketsToday = $tickets->where('created_at', '>=', $today)->count();
        $servedToday = $servedTickets->where('completed_at', '>=', $today)->count();

        $firstDate = $tickets->min('created_at');
        $daysSpan = $firstDate ? now()->diffInDays($firstDate) ?: 1 : 1;
        $averageTicketsPerDay = round($totalTickets / $daysSpan, 2);

        // Tickets grouped by day
        $ticketsPerDay = $tickets->groupBy(function ($ticket) {
            return $ticket->created_at->format('Y-m-d');
        })->map->count();

        $peakDay = $ticketsPerDay->sortDesc()->keys()->first();
        $peakDayCount = $ticketsPerDay->max();

        // Ticket counts by period using Eloquent queries directly to avoid filtering on collection
        $ticketsByPeriod = [
            'today' => Ticket::where('queue_id', $queue->id)->where('created_at', '>=', now()->startOfDay())->count(),
            'this_week' => Ticket::where('queue_id', $queue->id)->where('created_at', '>=', now()->startOfWeek())->count(),
            'this_month' => Ticket::where('queue_id', $queue->id)->where('created_at', '>=', now()->startOfMonth())->count(),
            'this_year' => Ticket::where('queue_id', $queue->id)->where('created_at', '>=', now()->startOfYear())->count(),
            'lifetime' => $totalTickets,
        ];

        $tickets = \App\Models\Ticket::where('queue_id', $queue->id)->get();
        $windows = $queue->windows;

        $aggregatedTickets = $this->aggregateTicketsByScale($tickets, $windows);


        // Tickets per window per day for last 30 days
        $startDate = now()->subDays(30)->startOfDay();

        $ticketsLast30Days = Ticket::select('window_id')
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('COUNT(*) as count')
            ->where('queue_id', $queue->id)
            ->where('created_at', '>=', $startDate)
            ->groupBy('window_id', 'date')
            ->orderBy('date')
            ->get();

        // Prepare dates for last 30 days (array of strings "Y-m-d")
        $dates = collect(range(0, 29))
            ->map(fn($daysAgo) => now()->subDays($daysAgo)->format('Y-m-d'))
            ->reverse()
            ->values();

        // Tickets count per window per date
        $windowTicketsByDate = [];
        foreach ($queue->windows as $window) {
            $windowTicketsByDate[$window->id] = $dates->map(function ($date) use ($ticketsLast30Days, $window) {
                $ticket = $ticketsLast30Days->firstWhere(fn($item) =>
                    $item->window_id == $window->id && $item->date == $date
                );
                return $ticket ? $ticket->count : 0;
            });
        }
        
        $analytics = [
            'totalTickets' => $totalTickets,
            'servedCount' => $servedCount,
            'averageQueueTime' => $averageQueueTime,
            'ticketsPerUser' => $ticketsPerUser,
            'ticketsToday' => $ticketsToday,
            'servedToday' => $servedToday,
            'averageTicketsPerDay' => $averageTicketsPerDay,
            'peakDay' => $peakDay,
            'peakDayCount' => $peakDayCount,
            'ticketsByPeriod' => $ticketsByPeriod,
            'windowTicketsByDate' => $windowTicketsByDate,
            'windowIds' => $windowIds,
            'dates' => $dates,
        ];

        return view('admin.QueueManagement', compact(
            'queue',
            'uniqueUsers',
            'accessList',
            'userWindows',
            'analytics',
            'aggregatedTickets'
        ));
    }



    function aggregateTicketsByScale($tickets, $windows)
    {
        $result = [
            'day' => [],
            'week' => [],
            'month' => [],
            'year' => [],
        ];

        foreach ($windows as $window) {
            $result['day'][$window->id] = [];
            $result['week'][$window->id] = [];
            $result['month'][$window->id] = [];
            $result['year'][$window->id] = [];
        }

        foreach ($tickets as $ticket) {
            $date = Carbon::parse($ticket->created_at);
            $windowId = $ticket->window_id;

            // By day
            $dayKey = $date->format('Y-m-d');
            $result['day'][$windowId][$dayKey] = ($result['day'][$windowId][$dayKey] ?? 0) + 1;

            // By week (ISO week)
            $weekKey = $date->format('o') . '-W' . $date->isoWeek();
            $result['week'][$windowId][$weekKey] = ($result['week'][$windowId][$weekKey] ?? 0) + 1;

            // By month
            $monthKey = $date->format('Y-m');
            $result['month'][$windowId][$monthKey] = ($result['month'][$windowId][$monthKey] ?? 0) + 1;

            // By year
            $yearKey = $date->format('Y');
            $result['year'][$windowId][$yearKey] = ($result['year'][$windowId][$yearKey] ?? 0) + 1;
        }

        return $result;
    }

    public function updateMediaAds(Request $request, $id)
    {
        $queue = Queue::findOrFail($id);

        // Delete existing media files if any
        $existingMedia = json_decode($queue->media_advertisement ?? '[]', true);
        if (!empty($existingMedia)) {
            foreach ($existingMedia as $existingFile) {
                if (Storage::disk('public')->exists($existingFile)) {
                    Storage::disk('public')->delete($existingFile);
                }
            }
        }

        // Handle new uploads (images and videos)
        $files = $request->file('File');
        $storedMedia = [];
        $mediaUrls = [];

        if ($files && is_array($files)) {
            foreach ($files as $file) {
                $path = $file->store('media', 'public');
                $storedMedia[] = $path;
                $mediaUrls[] = Storage::url($path);
            }
        }

        $queue->media_advertisement = json_encode($storedMedia);
        $queue->save();

        if ($request->expectsJson() || $request->wantsJson()) {
            if (empty($storedMedia)) {
                return response()->json(['success' => false, 'message' => 'No media files were uploaded.']);
            }
            return response()->json([
                'success' => true,
                'message' => 'Media files uploaded and updated successfully.',
                'media_urls' => $mediaUrls
            ]);
        }

        // Fallback: redirect with session message if not expecting JSON
        if (empty($storedMedia)) {
            return redirect()->back()->with('error', 'No media files were uploaded.');
        }
        return redirect()->back()->with('success', 'Media files uploaded and updated successfully.');
    }


    //view a window  from a queue to see who are the users who has access
    public function viewWindow($id)
    {
        $window = Window::with('users')->findOrFail($id);
        $users = $window->users;
        $allUsers = User::all(); // for name resolution in view

        $analytics = [
            'ticketsByUser' => [
                'day' => [],
                'week' => [],
                'year' => [],
            ],
            'averageQueueTime' => [
                'overall' => null,
            ],
            'averageHandleTime' => [
                'perUser' => [],
                'overall' => null,
            ]
        ];

        $overallQueueTimes = [];
        $overallHandleTimes = [];

        foreach ($users as $user) {
            $userTickets = Ticket::where('handled_by', $user->id)
                                ->where('window_id', $window->id)
                                ->where('status', 'Completed')
                                ->whereNotNull('called_at')
                                ->whereNotNull('completed_at')
                                ->get();

            $userHandleSeconds = 0;
            $userTicketCount = 0;

            foreach ($userTickets as $ticket) {
                $createdAt = Carbon::parse($ticket->created_at);
                $calledAt = Carbon::parse($ticket->called_at);
                $completedAt = Carbon::parse($ticket->completed_at);

                if ($calledAt->gt($createdAt) && $completedAt->gt($calledAt)) {
                    $queueTime = $calledAt->diffInSeconds($createdAt);
                    $handleTime = $completedAt->diffInSeconds($calledAt);

                    $overallQueueTimes[] = $queueTime;
                    $overallHandleTimes[] = $handleTime;

                    $userHandleSeconds += $handleTime;
                    $userTicketCount++;

                    $day = $createdAt->format('Y-m-d');
                    $analytics['ticketsByUser']['day'][$user->id][$day] =
                        ($analytics['ticketsByUser']['day'][$user->id][$day] ?? 0) + 1;

                    $week = $createdAt->format('o-\WW');
                    $analytics['ticketsByUser']['week'][$user->id][$week] =
                        ($analytics['ticketsByUser']['week'][$user->id][$week] ?? 0) + 1;

                    $year = $createdAt->format('Y');
                    $analytics['ticketsByUser']['year'][$user->id][$year] =
                        ($analytics['ticketsByUser']['year'][$user->id][$year] ?? 0) + 1;
                }
            }

            $analytics['averageHandleTime']['perUser'][$user->id] = $userTicketCount > 0
                ? round($userHandleSeconds / $userTicketCount)
                : null;
        }

        $analytics['averageQueueTime']['overall'] = count($overallQueueTimes) > 0
            ? round(array_sum($overallQueueTimes) / count($overallQueueTimes))
            : null;

        $analytics['averageHandleTime']['overall'] = count($overallHandleTimes) > 0
            ? round(array_sum($overallHandleTimes) / count($overallHandleTimes))
            : null;

        return view('admin.window', compact('window', 'users', 'allUsers', 'analytics'));
    }



    public function createWindow(Request $request, $queue_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $queue = Queue::findOrFail($queue_id);
        $Window = new Window([
            'name' => $request->name,
            'limit'=> 100,
            'description' => $request->description,
        ]);
        $queue->Windows()->save($Window);

        return redirect()->route('admin.queue.view', ['id' => $queue_id])->with('success', 'Window group added successfully.');
    }


    public function removeWindow($id)
    {
        $Window = Window::findOrFail($id);
        $Window->delete();

        return redirect()->route('admin.queue.view', ['id' => $Window->queue_id])->with('success', 'Window group removed successfully.');
    }

    //Adding and removing a users from a window group
    public function assignUserToWindow(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
    
        $Window = Window::findOrFail($id);
        $Window->users()->attach($request->user_id,["queue_id" => $Window->queue_id]);

        return redirect()->route('admin.window.view', ['id' => $id])->with('success', 'User assigned successfully.');
    }


    public function removeUserFromWindow($id, $user_id)
    {
        $Window = Window::findOrFail($id);
        $Window->users()->detach($user_id);

        return redirect()->route('admin.window.view', ['id' => $id])->with('success', 'User removed successfully.');
    }

    //Closing and opening of queue and windows For user and not admin
    public function updateAccess(Request $request, $user_id, $queue_id)
    {
        $accessList = WindowAccess::where('user_id', $user_id)
                                        ->where('queue_id', $queue_id)
                                        ->get();
    
        foreach ($accessList as $access) {
            $access->can_close_own_window = $request->input('can_close_own_window', false);
            $access->can_close_any_window = $request->input('can_close_any_window', false);
            $access->can_close_queue = $request->input('can_close_queue', false);
            $access->can_clear_queue = $request->input('can_clear_queue', false);
            $access->can_change_ticket_limit = $request->input('can_change_ticket_limit', false);
            $access->save();
        }
    
        return response()->json(['success' => true, 'message' => 'Access privileges updated successfully.']);
    }



    /**
     * User Methods
     **/ 
    public function myQueuesAndWindows()
    {
        $accountId = Session::get('account_id');
    
        $user = User::where('account_id', $accountId)->firstOrFail();
    
        // Get all window groups the user is related to
        $windows = $user->Windows()->with('queue')->get();
    
        // Get all queues related to those window groups
        $queues = Queue::whereHas('Windows', function ($query) use ($windows) {
            $query->whereIn('id', $windows->pluck('id'));
        })->get();
    
        return view('user.MyQueues', compact('queues', 'windows'));
    }

    // View a specific queue associated with the user

    public function manageQueue($id)
    {
        $queue = Queue::with('windows')->findOrFail($id);
        $WindowIds = $queue->windows->pluck('id');
    
        // Get the number of tickets generated today for each window
        $ticketsPerWindow = Ticket::whereIn('window_id', $WindowIds)
                                    ->whereDate('created_at', Carbon::today())
                                    ->selectRaw('window_id, count(*) as ticket_count')
                                    ->groupBy('window_id')
                                    ->pluck('ticket_count', 'window_id');
    
        return view('user.queuedetails', compact('queue', 'ticketsPerWindow'));
    }

    //Showing the dashboard for queuing
    public function queueingDashboard(Request $request, $id){
        $user_id = Auth::user()->id;
        // Fetch the Window and its associated Queue using the provided ID
        $window = Window::with('queue')->findOrFail($id);

        //This is 
        $windowAccess = WindowAccess::where('user_id', $user_id)
        ->where('window_id', $window->id)->first();

        if($window == null || $windowAccess==null){
            return redirect()->route('myQueues')->with('error', 'You do not have access to this window.');
        }else{
            // Pass the fetched data to the view
            return view('user.QueuingDashboard', compact('windowAccess', 'window', 'user_id'));
        }
    }

    //Other Controls
    public function toggleWindow(Request $request, $id){
        $user = Auth::user();
        $window = Window::findOrFail($id);
        $queueId = $request->input('queue_id');
        $queue = Queue::findOrFail($queueId);
    
        // Check if the user has privileges to toggle the window
        $accessExists = WindowAccess::where('user_id', $user->id)
                                     ->where('queue_id', $queueId)
                                     ->where(function ($query) {
                                         $query->where('can_close_own_window', true)
                                               ->orWhere('can_close_any_window', true);
                                     })
                                     ->exists();
    
        if (!$accessExists) {
            return response()->json(['success' => true, 'message' => 'You do not have the required privileges to perform this action.'], 403);
        }
    
        // Prevent opening the window if the queue is closed
        if ($queue->status === 'closed') {
            return response()->json(['success' => true, 'message' => 'The queue is closed. You cannot open the window.'], 403);
        }
    
        // Toggle the window status
        $window->status = $window->status === 'open' ? 'closed' : 'open';
        $window->save();
    
        broadcast(new QueueSettingsChanged($queue->id));
        return response()->json(['success' => true, 'message' => 'Window status updated successfully.']);
    }
    
    public function toggleQueue($id)
    {
        $user = Auth::user();
        $queue = Queue::findOrFail($id);
    
        // Check if the user has privileges to toggle the queue status
        $accessExists = WindowAccess::where('user_id', $user->id)
                                    ->where('queue_id', $id)
                                    ->where('can_close_queue', true)
                                    ->exists();
    
        if (!$accessExists) {
            return response()->json(['success' => false, 'message' => 'You do not have the required privileges to perform this action.'], 403);
        }
    
        // Toggle the queue status
        $newStatus = $queue->status === 'open' ? 'closed' : 'open';
        $queue->status = $newStatus;
        $queue->save();
    
        // Update the status of all windows associated with the queue
        $queue->windows()->update(['status' => $newStatus]);
        broadcast(new QueueSettingsChanged($queue->id));
        return response()->json(['success' => true, 'message' => 'Queue and windows status updated successfully.']);
    }
    
    
    public function clearQueue($id)
    {
        $user = Auth::user();
        $queue = Queue::findOrFail($id);
        $accessExists = WindowAccess::where('user_id', $user->id)
                                    ->where('queue_id', $id)
                                    ->where('can_clear_queue', true)
                                    ->exists();
    
                                    
        if (!$accessExists) {
            return response()->json(['success' => false, 'message' => 'You do not have the required privileges to perform this action.'], 403);
        }

        if (!$queue->tickets()->exists()) {
            return response()->json(['success' => true, 'message' => 'No tickets found.']);
        }
        
        $queue->tickets()
        ->whereIn('status', ['Waiting', 'On Hold'])
        ->delete();
    
        return response()->json(['success' => true, 'message' => 'Queue cleared successfully.']);
    }


    /**
     * Generate a unique 6-character code for the queue.
     */
    private function generateUniqueCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        do {
            $code = substr(str_shuffle($characters), 0, 6);
        } while (Queue::where('code', $code)->exists()); 
    
        return $code;
    }
}
