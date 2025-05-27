<!-- On-Hold Tickets Section -->
<div class="border p-6 rounded-lg shadow-lg">
    <!-- Flex Container -->
    <div class="flex items-center justify-between mb-4">
        <!-- Heading -->
        <h2 class="text-2xl font-extrabold text-gray-700 text-center">
            On-Hold Tickets
        </h2>
        <!-- Search Bar -->
        <div>
            <input
                type="text"
                id="search-on-hold"
                class="px-4 py-2 border rounded-lg"
                placeholder="Search for tickets..."
            />
        </div>
    </div>
    
    <table id="on-hold-tickets" class="w-full border-none bg-white border rounded-lg shadow-md">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-800 uppercase tracking-wider border-b">
                    Ticket Code
                </th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-800 uppercase tracking-wider border-b">
                    Called At
                </th>
                <th id="sort-action" class="px-6 py-3 text-left text-sm font-medium text-gray-800 uppercase tracking-wider border-b text-center" >
                    Action
                </th>
            </tr>
        </thead>
        <tbody id="on-hold-tickets-body">
            <tr>
                <td class="px-6 py-4 text-gray-600" colspan="2">No on-hold tickets available.</td>
            </tr>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4 flex justify-center">
        <button id="previous-on-hold" class="px-4 py-2 bg-gray-600 text-white rounded-lg" disabled>Previous</button>
        <span id="on-hold-page-number" class="mx-4 text-gray-700">Page 1</span>
        <button id="next-on-hold" class="px-4 py-2 bg-gray-600 text-white rounded-lg">Next</button>
    </div>
</div>

<script>
    // Pagination variables for On-Hold Tickets
    let onHoldPage = 1;
    let onHoldTicketsPerPage = 20;  
    let totalOnHoldPages = 1;
    let searchQueryOnHoldTicket = '';  // Store search query

    // Get On-Hold Tickets with Search
    function getOnHoldTickets(page = 1) {

        function formatDate(timestamp) {
            const date = new Date(timestamp);
            const options = { month: 'long' }; // Get full month name
            const month = new Intl.DateTimeFormat('en-US', options).format(date);
            const day = date.getDate();
            const year = date.getFullYear();
            const hours = date.getHours();
            const minutes = date.getMinutes().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const formattedHours = hours % 12 || 12; // Convert 24-hour to 12-hour

            return `${month} ${day}, ${year}, ${formattedHours}:${minutes} ${ampm}`;
        }

        $.ajax({
            url: `{{ route('getTicketsOnHold', ['window_id' => $window->id]) }}?page=${page}&per_page=${onHoldTicketsPerPage}&search=${searchQueryOnHoldTicket}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    totalOnHoldPages = response.total_pages;

                    // Check if there are tickets to display
                    if (response.tickets.length === 0) {
                        $('#on-hold-tickets-body').html(
                            `<tr>
                                <td class="px-6 py-4 text-gray-600" colspan="2">No on-hold tickets available.</td>
                            </tr>`
                        );
                    } else {
                        $('#on-hold-tickets-body').html(
                            response.tickets.map(ticket =>
                                `<tr>
                                    <td class="px-6 py-4 text-gray-600">${ticket.code}</td>
                                    <td class="px-6 py-4 text-gray-600">${formatDate(ticket.called_at)}</td>
                                    <td class="px-6 py-4 text-gray-600 text-center">
                                        <button 
                                            onclick="handleOnHoldTicket(${ticket.id})"
                                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-md border border-gray-400">
                                            Handle Ticket
                                        </button>
                                    </td>
                                </tr>`
                            ).join('')
                        );
                    }

                    $('#on-hold-page-number').text(`Page ${page}`);
                    $('#previous-on-hold').prop('disabled', page === 1);
                    $('#next-on-hold').prop('disabled', page === totalOnHoldPages);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Error while fetching on-hold tickets");
            }
        });
    }

    // Handle Previous/Next Pagination for On-Hold Tickets
    $('#previous-on-hold').on('click', function() {
        if (onHoldPage > 1) {
            onHoldPage--;
            getOnHoldTickets(onHoldPage);
        }
    });

    $('#next-on-hold').on('click', function() {
        if (onHoldPage < totalOnHoldPages) {
            onHoldPage++;
            getOnHoldTickets(onHoldPage);
        }
    });

    // Handle Search Input
    $('#search-on-hold').on('input', function() {
        searchQueryOnHoldTicket = $(this).val(); // Get the search query
        onHoldPage = 1; // Reset to the first page when searching
        getOnHoldTickets(onHoldPage); // Fetch tickets based on search query
    });

    function handleOnHoldTicket(ticketId) {
        // Construct the URL with a placeholder
        const url = `{{ route('handleTicket', ['window_id' => $window->id, 'ticket_id' => '__TICKET_ID__']) }}`;

        // Replace the placeholder with the actual ticketId
        const finalUrl = url.replace('__TICKET_ID__', ticketId);

        console.log(finalUrl);
        $.ajax({
            url: finalUrl,
            method: 'GET',
            success: function(response) {

                if (response.success) {
                    location.reload();
                    alert(response['message']);
                } else {
                    alert(response['message']);
                }
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Error while fetching data");
            }
        });
    }
 </script>
