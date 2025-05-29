<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/TicketReceipt.blade.php -->
<x-App>
    <x-slot name="content">
        <div class="bg-gray-50 min-h-screen flex items-center justify-center px-6 lg:px-0">
            <div class="p-12 border border-gray-200 rounded-xl bg-white shadow-lg w-full lg:w-1/2">
                <h1 class="text-5xl font-extrabold text-green-400 text-center mb-4">Your Ticket</h1>
                <p class="text-lg text-gray-600 text-center mb-8">
                    Thank you for queuing with us! Here is your Ticket information. Please note that this Ticket is valid for one-time use only.
                    Take a photo or screenshot of this Ticket for reference.
                </p>
                
                <div class="text-center">
                    <p class="text-2xl text-gray-800 mb-4"><strong>Code:</strong> {{ $Ticket->code }}</p>
                    <p class="text-xl text-gray-600 mb-4"><strong>Ticket Position:</strong> {{ $Ticket->ticket_number ?? 'N/A' }}</p>
                    <p class="text-xl text-gray-600 mb-4"><strong>Name:</strong> {{ $Ticket->name ?? 'N/A' }}</p>
                    <p class="text-xl text-gray-600 mb-4"><strong>Window:</strong> {{ $Ticket->window->name }}</p>
                    <p class="text-xl text-gray-600 mb-4"><strong>Status:</strong> {{ $Ticket->status }}</p>
                </div>
                
                <div class="flex justify-center mt-8">
                    <a href="{{ route('ticketing', ['code' => $Queue->code]) }}" class="px-8 py-4 bg-indigo-600 text-white text-lg font-bold rounded-lg shadow hover:bg-indigo-700 transition-all">
                        Back to Ticketing
                    </a>
                </div>
            </div>
        </div>
    </x-slot>
</x-App>