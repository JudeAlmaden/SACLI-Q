<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/QueuingDashboard.blade.php -->
<x-Dashboard>
    <x-slot name="content">

        <style>
            .control-buttons button {
                transition: transform 0.3s ease, background-color 0.3s ease;
                height:  150px; /* Set a fixed height for all buttons */
                font-size: 1.2rem; /* Increase font size for better visibility */
            }
        </style>

        <div class="mt-16 p-6 sm:ml-64 bg-white min-h-screen rounded-lg shadow-lg">
            <!-- Header Section -->
            <header class="mb-8">
            <div class="flex items-center space-x-4">
                <i class="fas fa-window-maximize text-4xl text-green-600"></i>
                <div>
                <h1 class="text-4xl font-extrabold text-green-800">{{ $window->name }}</h1>
                <p class="text-md text-green-700">
                   <span class="font-semibold">{{ $window->queue->name }}</span>
                </p>
                </div>
            </div>
            </header>

            <div class="grid grid-rows-1 grid-cols-2 gap-x-3">
                <div class="grid grid-cols-1 gap-3">
                    <!-- window Input Section -->
                    <section class="p-6 bg-white border border-green-300 rounded-lg shadow-md">
                        <form id="window-form" class="space-y-6">
                            <div class="flex items-center space-x-4">
                                <label for="window-name" class="w-12/12 block font-medium text-green-700">
                                    Window Name:
                                </label>
                                <input 
                                    type="text" 
                                    id="window-name" 
                                    name="window_name" 
                                    placeholder="{{ $windowAccess->window_name ?? 'Ex: Window 1' }}"
                                    class="flex-grow w-9/12 px-3 py-2 border border-green-400 focus:border-green-600 focus:ring-green-500 bg-white text-green-900 rounded-md"
                                >
                                <button type="submit" class="p-2 w-3/12 bg-green-600 text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 rounded-md">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </section>

                    <!-- Combined Description Section -->
                    <section class="p-6 bg-white border border-green-300 rounded-lg shadow-md">
                        <div class="p-4 bg-green-600 text-white rounded-lg shadow-lg">
                            <h2 class="text-xl font-bold mb-2">Number of tickets left: <span id="upcoming-tickets-count" class="text-2xl font-semibold"></span></h2>
                              <h2 class="text-xl font-bold mb-2">Number of tickets handled: <span id="completed-tickets-count" class="text-2xl font-semibold"></span></h2>
                        </div>
                    </section>
                </div>
                
                <div class="grid grid-cols-1">
                    <!-- Currently Handling Section -->
                    <section class="p-8 bg-white border-2 border-green-600 rounded-lg shadow-lg">
                        <h2 class="text-4xl font-extrabold text-green-800 mb-6 text-center">Currently Handling</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-2xl">
                            <p class="w-full flex items-center justify-between text-green-800 bg-green-50 p-4 rounded-md shadow-md">
                                <strong class="block">Ticket:</strong> 
                                <span id="current-ticket-number" class="font-semibold text-green-600">N/A</span>
                            </p>
                            <p class="w-full flex items-center justify-between text-green-800 bg-green-50 p-4 rounded-md shadow-md">
                                <strong class="block">Name:</strong> 
                                <span id="current-ticket-name" class="font-semibold text-green-600">N/A</span>
                            </p>
                        </div>
                    </section>
                </div>
            </div>


            <!-- Actions Section -->   
            <section class="mt-6 p-6 bg-white border border-gray-300 0 rounded-lg border-green-300 r shadow-md">
                <h2 class="text-xl font-bold text-gray-800  mb-4">Actions</h2>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 control-buttons">
                    <button 
                        id="next-ticket"
                        class="flex items-center justify-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Move to the next ticket in the queue">
                        <i class="fa-solid fa-play w-5 h-5 flex items-center justify-center"></i>
                        <span class="whitespace-nowrap">Get Next Ticket</span>
                    </button>
                    
                    <button 
                        id="next-ticket-hold"
                        class="flex items-center justify-center space-x-2 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Get a ticket that is on hold">
                        <i class="fa-solid fa-clock w-5 h-5 flex items-center justify-center"></i>
                        <span class="whitespace-nowrap">Get from hold</span>
                    </button>
                    
                    <button 
                        id="complete-ticket"
                        class="flex items-center justify-center space-x-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Mark the current ticket as completed">
                        <i class="fa-solid fa-check w-5 h-5 flex items-center justify-center"></i>
                        <span class="whitespace-nowrap">Complete</span>
                    </button>
                    
                    <button 
                        id="hold-ticket"
                        class="flex items-center justify-center space-x-2 bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Put the current ticket on hold">
                        <i class="fa-solid fa-pause w-5 h-5 flex items-center justify-center"></i>
                        <span class="whitespace-nowrap">Put on hold</span>
                    </button>
                    
                    <button 
                        id="call-ticket"
                        class="flex items-center justify-center space-x-2 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Call the current ticket to the window">
                        <i class="fa-solid fa-volume-high w-5 h-5 flex items-center justify-center"></i>
                        <span class="whitespace-nowrap">Call</span>
                    </button>
                </div>
            </section>



            <!-- Add HTML elements to display the tickets -->
            <div class="mt-10 grid grid-cols-2 gap-6">
                {{-- Passing the $Window from controller as prop --}}
                <x-TableOnHoldTickets :window="$window" />
                <x-TableUpcomingTickets :window="$window" />
                <x-TableCompletedTickets :window="$window" />
            </div>
        </div>
    </x-slot>
</x-Dashboard>



<script>
$(document).ready(function() {
    var token = "{{ session('token') }}";

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

        const ticketNumber = $('#current-ticket-number').text().replace(/\D/g, '');

        $.ajax({
            url: "{{ route('broadcast.callTicket.event') }}", // no need for route parameters
            method: 'POST',
            data: {
                window_name: "{{ $windowAccess->window_name}}", //name of the user with access to the window
                ticket_number: ticketNumber,  
                queue_id: "{{ $window->queue_id}}", //queue code is alias for queueID
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
                    $('#current-ticket-name').text(response.name ?? 'N/A');

                    $('#next-ticket').prop('disabled', true).addClass('opacity-60'); 
                    $('#next-ticket-hold').prop('disabled', true).addClass('opacity-60');

                    $('#complete-ticket').prop('disabled', false).removeClass('opacity-60');
                    $('#hold-ticket').prop('disabled', false).removeClass('opacity-60');
                    $('#call-ticket').prop('disabled', false).removeClass('opacity-60');
                } else {
                    $('#current-ticket-number').text('N/A');
                    $('#current-ticket-name').text('N/A');
                    
                    $('#next-ticket').prop('disabled', false).removeClass('opacity-60'); 
                    $('#next-ticket-hold').prop('disabled', false).removeClass('opacity-60');

                    $('#complete-ticket').prop('disabled', true).addClass('opacity-60');
                    $('#hold-ticket').prop('disabled', true).addClass('opacity-60');
                    $('#call-ticket').prop('disabled', true).addClass('opacity-60');
                }
            },
            error: function(xhr, status, error) {
                $('#next-ticket').prop('disabled', false).removeClass('opacity-60'); 
                $('#next-ticket-hold').prop('disabled', false).removeClass('opacity-60');

                $('#complete-ticket').prop('disabled', true).addClass('opacity-60');
                $('#hold-ticket').prop('disabled', true).addClass('opacity-60');
                $('#call-ticket').prop('disabled', true).addClass('opacity-60');

                $('#current-ticket-number').text('N/A');
                $('#current-ticket-name').text('N/A');
                alert("Error while fetching current tickets");
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
        
        // Add a timeout before calling getLiveData
        setTimeout(() => {
        getWindowUserData();
        getUpcomingTickets(upcomingTicketsPage);
        }, 3000);
    });
});
</script>
