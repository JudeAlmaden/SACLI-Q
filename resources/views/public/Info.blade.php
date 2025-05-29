<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/TicketReceipt.blade.php -->
<x-App>
    <x-slot name="content">
        <div class="container mx-auto max-w-2xl px-4 py-8">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Where's My Ticket?</h1>
                <p class="text-gray-600">Track your ticket status instantly</p>
            </div>
    
            <!-- Search Card -->
            <div class="bg-white rounded-lg shadow-md mb-8">
                <div class="p-6">
                    <form id="ticketTrackForm" action="{{ route('info') }}" method="GET" class="space-y-6">
                        <!-- Ticket Input -->
                        <div class="space-y-2">
                            <label for="ticketCode" class="block text-sm font-medium text-gray-700">Ticket Code</label>
                            <div class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                                <input 
                                    type="text" 
                                    id="ticketCode"
                                    name="ticketCode"
                                    class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                                    placeholder="Enter your ticket number (e.g., 0003)"
                                    required
                                >
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label for="queue_id" class="block text-sm font-medium text-gray-700">Queue</label>
                            <select 
                                id="queue_id" 
                                name="queue_id" 
                                class="w-full rounded-lg border border-gray-300 pl-3 pr-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                                required
                            >
                                <option value="">Select a queue</option>
                                @foreach($allQueues as $queue)
                                    <option value="{{ $queue->id }}" {{ old('queue_id') == $queue->id ? 'selected' : '' }}>
                                        {{ $queue->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
    
                        <!-- Track Button -->
                        <button 
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Track My Ticket
                        </button>
                    </form>
                </div>
            </div>

@if(isset($ticket))
    <div class="mt-6 p-5 bg-white border border-blue-200 rounded-md shadow-sm">
        <h2 class="text-xl font-semibold text-blue-700 text-center mb-4">Ticket Found</h2>

        <div class="space-y-3 text-sm text-gray-700">
            <p><strong>Ticket Code:</strong> {{ $ticket->code }}</p>
            <p><strong>Status:</strong> {{ $ticket->status }}</p>
            <p><strong>Ticket Number:</strong> {{ $ticket->ticket_number ?? 'Not assigned' }}</p>
            <p><strong>Name:</strong> {{ $ticket->name ?? 'Not assigned' }}</p>

            @if($ticket->status === 'Waiting' && isset($ticketPosition))
                <p class="bg-blue-50 text-blue-700 p-2 rounded-md">
                    Your position in the queue: <strong>{{ $ticketPosition }}</strong>
                </p>
            @elseif($ticket->status === 'On Hold')
                <p class="bg-blue-50 text-blue-700 p-2 rounded-md">
                    This ticket has already been called and is currently on hold.
                </p>
            @elseif($ticket->status === 'Completed')
                <p class="bg-blue-50 text-blue-700 p-2 rounded-md">
                    This ticket has been marked as completed.
                </p>
            @endif

            <p><strong>Queue:</strong> {{ $ticket->queue->name ?? 'Not assigned' }}</p>
            <p><strong>Window:</strong> {{ $ticket->window->name ?? 'Not assigned' }}</p>
        </div>
    </div>
@else
    <div class="mt-6 p-5 bg-white border border-blue-200 rounded-md shadow-sm">
        <h2 class="text-xl font-semibold text-blue-700 text-center mb-2">No Ticket Found</h2>
        <p class="text-center text-gray-600">The ticket with the provided code does not exist or has already been completed.</p>
    </div>
@endif


    </x-slot>
</x-App>
