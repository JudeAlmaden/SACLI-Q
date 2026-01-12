<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/QueuingDashboard.blade.php -->
<x-Dashboard>
    <x-slot name="content">
        @php
            $hasWindowName = !empty($windowAccess->window_name);
        @endphp
        <style>
            .control-btn {
                transition: all 0.2s ease;
            }
            .control-btn:active {
                transform: scale(0.98);
            }
        </style>

        <!-- Modal Overlay for Missing Window Name -->
        @if(!$hasWindowName)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 animate-fade-in">
                <div class="text-center mb-6">
                    <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Window Name Required</h2>
                    <p class="text-gray-600">Please set a name for this window before you can start processing tickets.</p>
                </div>

                <form id="modal-window-form" class="space-y-4">
                    <div>
                        <label for="modal-window-name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Window Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="modal-window-name" 
                            name="window_name" 
                            placeholder="e.g., Window 1, Cashier A, etc."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition"
                            required
                            autofocus
                        >
                    </div>
                    <button 
                        type="submit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2"
                    >
                        <i class="fas fa-save"></i>
                        <span>Save and Continue</span>
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Hidden Audio for Notification -->
        <audio id="notification-sound" preload="auto">
            <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZURE" type="audio/wav">
        </audio>

        <div class="mt-16 p-6 sm:ml-64 bg-gray-50 min-h-screen {{ !$hasWindowName ? 'blur-sm pointer-events-none' : '' }}">
            
            
            <!-- Top Controls & Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 p-3 rounded-lg text-green-700">
                        <i class="fas fa-window-maximize text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $window->name }}</h1>
                        <p class="text-sm text-gray-500">Queue: <span class="font-semibold text-green-700">{{ $window->queue->name }}</span></p>
                    </div>
                </div>

                <!-- Window Name Edit (Compact) -->
                <form id="window-form" class="flex items-center bg-white p-1.5 rounded-lg border border-gray-200 shadow-sm md:w-auto w-full">
                    <input 
                        type="text" 
                        id="window-name" 
                        name="window_name" 
                        placeholder="{{ $windowAccess->window_name ?? 'Rename Window...' }}"
                        class="border-none focus:ring-0 text-sm md:w-48 w-full"
                    >
                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded-md text-sm font-medium transition-colors">
                        Save
                    </button>
                </form>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Currently Handling (Big Card) -->
                <div class="md:col-span-1 bg-white rounded-xl shadow-sm border border-green-200 p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <i class="fas fa-bullhorn text-6xl text-green-700"></i>
                    </div>
                    <h3 class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1">Currently Serving</h3>
                    <div class="mt-4">
                        <div class="flex items-baseline space-x-2">
                             <span class="text-gray-400 text-lg">#</span>
                             <span id="current-ticket-number" class="text-5xl font-extrabold text-green-700 tracking-tight">--</span>
                        </div>
                        <p id="current-ticket-name" class="mt-2 text-lg font-medium text-gray-700 truncate min-h-[1.75rem]">
                            Waiting for ticket...
                        </p>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-2 gap-4 md:col-span-2">
                     <div id="tickets-waiting-card" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center transition-all duration-300">
                        <span class="text-sm font-medium text-gray-500">Tickets Waiting</span>
                        <span id="upcoming-tickets-count" class="text-3xl font-bold text-gray-900 mt-2">--</span>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center">
                        <span class="text-sm font-medium text-gray-500">Tickets Handled</span>
                        <span id="completed-tickets-count" class="text-3xl font-bold text-gray-900 mt-2">--</span>
                    </div>
                </div>
            </div>

            <!-- Main Actions -->
            <div class="mb-8">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <button id="next-ticket" {{ !$hasWindowName ? 'disabled' : '' }} class="control-btn flex flex-col items-center justify-center p-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm hover:shadow-md h-32 {{ !$hasWindowName ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fa-solid fa-play text-2xl mb-2"></i>
                        <span class="font-bold">Next Ticket</span>
                    </button>

                    <button id="call-ticket" {{ !$hasWindowName ? 'disabled' : '' }} class="control-btn flex flex-col items-center justify-center p-4 bg-teal-600 hover:bg-teal-700 text-white rounded-xl shadow-sm hover:shadow-md h-32 {{ !$hasWindowName ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fa-solid fa-volume-high text-2xl mb-2"></i>
                        <span class="font-bold">Call Again</span>
                    </button>

                    <button id="hold-ticket" {{ !$hasWindowName ? 'disabled' : '' }} class="control-btn flex flex-col items-center justify-center p-4 bg-orange-500 hover:bg-orange-600 text-white rounded-xl shadow-sm hover:shadow-md h-32 {{ !$hasWindowName ? 'opacity-50 cursor-not-allowed' : '' }}">
                         <i class="fa-solid fa-pause text-2xl mb-2"></i>
                        <span class="font-bold">Put on Hold</span>
                    </button>

                     <button id="next-ticket-hold" {{ !$hasWindowName ? 'disabled' : '' }} class="control-btn flex flex-col items-center justify-center p-4 bg-indigo-500 hover:bg-indigo-600 text-white rounded-xl shadow-sm hover:shadow-md h-32 {{ !$hasWindowName ? 'opacity-50 cursor-not-allowed' : '' }}">
                         <i class="fa-solid fa-clock-rotate-left text-2xl mb-2"></i>
                        <span class="font-bold">Call from Hold</span>
                    </button>

                    <button id="complete-ticket" {{ !$hasWindowName ? 'disabled' : '' }} class="control-btn flex flex-col items-center justify-center p-4 bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-sm hover:shadow-md h-32 {{ !$hasWindowName ? 'opacity-50 cursor-not-allowed' : '' }}">
                         <i class="fa-solid fa-check text-2xl mb-2"></i>
                        <span class="font-bold">Complete</span>
                    </button>
                </div>
            </div>

            <!-- Tables Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-[500px] flex flex-col">
                    <div class="flex-1 overflow-auto p-2">
                         <x-TableUpcomingTickets :window="$window" />
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-[500px] flex flex-col">
                    <div class="flex-1 overflow-auto p-2">
                        <x-TableOnHoldTickets :window="$window" />
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-[500px] flex flex-col">
                    <div class="flex-1 overflow-auto p-2">
                        <x-TableCompletedTickets :window="$window" />
                    </div>
                </div>
            </div>

        </div>
    </x-slot>
</x-Dashboard>

<script>
$(document).ready(function() {
    var token = "{{ session('token') }}";

    var ticketNumber = null
    // Updating Window name
    // Handle both modal and inline form submissions
    function submitWindowName(formId, inputId) {
        const windowName = $(inputId).val();
        if (!windowName || windowName.trim() === '') {
            alert('Please enter a window name');
            return;
        }

        $.ajax({
            url: "{{ route('updateWindowName', ['id' => $window->id]) }}",
            method: 'POST',
            data: {
                window_name: windowName,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred while updating the window.');
            }
        });
    }

    $('#window-form').on('submit', function (e) {
        e.preventDefault();
        submitWindowName('#window-form', '#window-name');
    });

    $('#modal-window-form').on('submit', function (e) {
        e.preventDefault();
        submitWindowName('#modal-window-form', '#modal-window-name');
    });

    //Control Buttons
    $('#next-ticket').on('click', function(event) {
        event.preventDefault();
        $.ajax({
            url: "{{ route('getNextTicketForWindow', ['window_id' => $window->id]) }}",
            method: 'GET',
            success: function(response) {

                if(response.success) {
                    getCurrentTicketData();
                    getTablesAndData();
                    alert(response['message']);
                } else {
                    alert(response['message']);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Error while fetching data");
            }
        });
    });

    $('#next-ticket-hold').on('click', function(event) {
        event.preventDefault();
        $.ajax({
            url: "{{ route('getFromTicketsOnHold', ['window_id' => $window->id]) }}",
            method: 'GET',
            success: function(response) {
                console.log(response);
                if(response.success) {
                    getTablesAndData();
                    alert(response['message']);
                } else {
                    alert(response['message']);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Error while fetching data");
            }
        });
    });

    $('#complete-ticket').on('click', function(event) {
        event.preventDefault();
        $.ajax({
            url: "{{ route('setToComplete', ['window_id' => $window->id]) }}",
            method: 'GET',
            success: function(response) {
                console.log(response);
                if(response.success) {
                    getTablesAndData();
                    alert(response['message']);
                } else {
                    alert(response['message']);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Error while fetching data");
            }
        });
    });

    $('#hold-ticket').on('click', function(event) {
        event.preventDefault();
        $.ajax({
            url: "{{ route('setToHold', ['window_id' => $window->id]) }}",
            method: 'GET',
            success: function(response) {
                console.log(response);
                if(response.success) {
                    getTablesAndData();
                    alert(response['message']);
                } else {
                    alert(response['message']);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Error while fetching data");
            }
        });
    });
   
    $('#call-ticket').on('click', function(event) {
        event.preventDefault();

        $.ajax({
            url: "{{ route('broadcast.callTicket.event') }}", 
            method: 'POST',
            data: {
                window_name: "{{ $windowAccess->window_name}}",
                ticket_number: ticketNumber,  
                queue_id: "{{ $window->queue_id}}", 
                _token: "{{ csrf_token() }}"  
            },
            success: function(response) {
                if (response.success) {
                    getTablesAndData();
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Error while fetching data");
            }
        });
    });

    //Fetch methods for getting data
    function getCurrentTicketData() {
        
        $.ajax({
            url: "{{ route('getCurrentTicketData', ['window_id' => $window->id]) }}",
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    $('#current-ticket-number').text(response.ticket_number ?? 'N/A');
                    $('#current-ticket-name').text(response.name ?? 'waiting for ticket...');

                    ticketNumber = response.ticket_number;
                    $('#next-ticket').prop('disabled', true).addClass('opacity-60 cursor-not-allowed bg-blue-300').removeClass('bg-blue-600 hover:bg-blue-700'); 
                    $('#next-ticket-hold').prop('disabled', true).addClass('opacity-60 cursor-not-allowed bg-indigo-300').removeClass('bg-indigo-500 hover:bg-indigo-600');

                    $('#complete-ticket').prop('disabled', false).removeClass('opacity-60 cursor-not-allowed bg-green-300').addClass('bg-green-600 hover:bg-green-700');
                    $('#hold-ticket').prop('disabled', false).removeClass('opacity-60 cursor-not-allowed bg-orange-300').addClass('bg-orange-500 hover:bg-orange-600');
                    $('#call-ticket').prop('disabled', false).removeClass('opacity-60 cursor-not-allowed bg-teal-300').addClass('bg-teal-600 hover:bg-teal-700');
                } else {
                    $('#current-ticket-number').text('--');
                    $('#current-ticket-name').text('Waiting for ticket...');
                    
                    $('#next-ticket').prop('disabled', false).removeClass('opacity-60 cursor-not-allowed bg-blue-300').addClass('bg-blue-600 hover:bg-blue-700'); 
                    $('#next-ticket-hold').prop('disabled', false).removeClass('opacity-60 cursor-not-allowed bg-indigo-300').addClass('bg-indigo-500 hover:bg-indigo-600');

                    $('#complete-ticket').prop('disabled', true).addClass('opacity-60 cursor-not-allowed bg-green-300').removeClass('bg-green-600 hover:bg-green-700');
                    $('#hold-ticket').prop('disabled', true).addClass('opacity-60 cursor-not-allowed bg-orange-300').removeClass('bg-orange-500 hover:bg-orange-600');
                    $('#call-ticket').prop('disabled', true).addClass('opacity-60 cursor-not-allowed bg-teal-300').removeClass('bg-teal-600 hover:bg-teal-700');
                }
            },
            error: function(xhr, status, error) {
                $('#next-ticket').prop('disabled', false); 
                $('#next-ticket-hold').prop('disabled', false);

                $('#complete-ticket').prop('disabled', true);
                $('#hold-ticket').prop('disabled', true);
                $('#call-ticket').prop('disabled', true);

                $('#current-ticket-number').text('N/A');
                $('#current-ticket-name').text('N/A');
            }
        });
    }

    function getWindowUserData() {
        $.ajax({
            url: "{{ route('getWindowUserData', ['window_id' => $window->id, 'user_id'=>$user_id ]) }}",
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const count = response.upcoming_tickets_count;
                    $('#upcoming-tickets-count').text(count);
                    $('#completed-tickets-count').text(response.completed_tickets_count);
                    
                    // Dynamic color highlighting based on queue volume
                    const card = $('#tickets-waiting-card');
                    card.removeClass('border-gray-200 border-yellow-300 bg-yellow-50 border-red-400 bg-red-50 border-green-300 bg-green-50');
                    $('#upcoming-tickets-count').removeClass('text-gray-900 text-yellow-700 text-red-700 text-green-700');
                    
                    if (count === 0) {
                        card.addClass('border-green-300 bg-green-50');
                        $('#upcoming-tickets-count').addClass('text-green-700');
                    } else if (count >= 1 && count <= 5) {
                        card.addClass('border-gray-200');
                        $('#upcoming-tickets-count').addClass('text-gray-900');
                    } else if (count >= 6 && count <= 15) {
                        card.addClass('border-yellow-300 bg-yellow-50');
                        $('#upcoming-tickets-count').addClass('text-yellow-700');
                    } else {
                        card.addClass('border-red-400 bg-red-50');
                        $('#upcoming-tickets-count').addClass('text-red-700');
                    }
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Error while fetching upcoming tickets count");
            }
        });
    }

    function getTablesAndData() {
        getWindowUserData();
        getCurrentTicketData();
        getCompletedTickets(completedPage);
        getOnHoldTickets(onHoldPage);
        getUpcomingTickets(upcomingTicketsPage);
    }

    // Notification sound function
    function playNotification() {
        const audio = document.getElementById('notification-sound');
        if (audio) {
            audio.play().catch(e => console.log('Audio play failed:', e));
        }
    }

    getTablesAndData();

    //For Synchronous Session With Live View
    Echo.channel('live-queue.{{$window->queue_id}}')
    .listen('NewTicketEvent', () => {
        playNotification(); // Play sound notification
        
        setTimeout(() => {
        getWindowUserData();
        getUpcomingTickets(upcomingTicketsPage);
        }, 3000);
    });
});
</script>
