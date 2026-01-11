<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/QueuingDashboard.blade.php -->
<x-Dashboard>
    <x-slot name="content">
        <style>
            .control-btn {
                transition: all 0.2s ease;
            }
            .control-btn:active {
                transform: scale(0.98);
            }
        </style>

        <div class="mt-16 p-6 sm:ml-64 bg-gray-50 min-h-screen">
            
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
                     <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center">
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
                    <button id="next-ticket" class="control-btn flex flex-col items-center justify-center p-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm hover:shadow-md h-32">
                        <i class="fa-solid fa-play text-2xl mb-2"></i>
                        <span class="font-bold">Next Ticket</span>
                    </button>

                    <button id="call-ticket" class="control-btn flex flex-col items-center justify-center p-4 bg-teal-600 hover:bg-teal-700 text-white rounded-xl shadow-sm hover:shadow-md h-32">
                        <i class="fa-solid fa-volume-high text-2xl mb-2"></i>
                        <span class="font-bold">Call Again</span>
                    </button>

                    <button id="hold-ticket" class="control-btn flex flex-col items-center justify-center p-4 bg-orange-500 hover:bg-orange-600 text-white rounded-xl shadow-sm hover:shadow-md h-32">
                         <i class="fa-solid fa-pause text-2xl mb-2"></i>
                        <span class="font-bold">Put on Hold</span>
                    </button>

                     <button id="next-ticket-hold" class="control-btn flex flex-col items-center justify-center p-4 bg-indigo-500 hover:bg-indigo-600 text-white rounded-xl shadow-sm hover:shadow-md h-32">
                         <i class="fa-solid fa-clock-rotate-left text-2xl mb-2"></i>
                        <span class="font-bold">Call from Hold</span>
                    </button>

                    <button id="complete-ticket" class="control-btn flex flex-col items-center justify-center p-4 bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-sm hover:shadow-md h-32">
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
    //Updating Window name
    $('#window-form').on('submit', function (e) {
        e.preventDefault();

      const windowId = {{ $window->id ?? 'null' }};
         const windowName = $('#window-name').val();

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
                     alert(response.message);
                 } else {
                     alert(response.message);
                 }
             },
             error: function (xhr, status, error) {
                 console.error('Error:', error);
                 alert('An error occurred while updating the window.');
             }
         });
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
                    $('#upcoming-tickets-count').text(response.upcoming_tickets_count);
                    $('#completed-tickets-count').text(response.completed_tickets_count);
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

    getTablesAndData();

    //For Synchronous Session With Live View
    Echo.channel('live-queue.{{$window->queue_id}}')
    .listen('NewTicketEvent', () => {
        
        setTimeout(() => {
        getWindowUserData();
        getUpcomingTickets(upcomingTicketsPage);
        }, 3000);
    });
});
</script>
