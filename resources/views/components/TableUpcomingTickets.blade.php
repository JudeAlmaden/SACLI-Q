<style>
    /* Default arrow on hover */
    th .sort-arrow::after {
        content: '↕'; /* Default arrow */
        font-size: 12px;
        margin-left: 5px;
        transition: content 0.3s ease;
    }

    /* Sorting arrows when sorted */
    .sort-asc .sort-arrow::after {
        content: '↑'; /* Up arrow for ascending */
    }

    .sort-desc .sort-arrow::after {
        content: '↓'; /* Down arrow for descending */
    }

    /* Hover effect */
    th:hover {
        background-color: #f3f4f6; /* Light background on hover */
        cursor: pointer;
    }

    table{
        font-size: 14px
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="border p-6 rounded-lg shadow-lg">
    <div class="flex items-center justify-between mb-4">
        <!-- Heading -->
        <h2 class="text-2xl font-extrabold text-gray-700 text-center">
           Upcoming Tickets
        </h2>
        <!-- Search Bar -->
        <div>
            <input
                type="text"
                id="search-upcoming"
                class="px-4 py-2 border rounded-lg"
                placeholder="Search for tickets..."
            />
        </div>
    </div>

    <table id="completed-tickets" class="w-full border-none bg-white border rounded-lg shadow-md">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-800 uppercase tracking-wider border-b">
                    Ticket Code
                </th>
                <th id="sort-ticket_number-upcoming_ticket" class="px-6 py-3 text-left text-sm font-medium text-gray-800 uppercase tracking-wider border-b">
                    Ticket Number <span class="sort-arrow"></span>
                </th>
                <th id="sort-created_at-upcoming_ticket" class="px-6 py-3 text-left text-sm font-medium text-gray-800 uppercase tracking-wider border-b">
                    Created At <span class="sort-arrow"></span>
                </th>
                <th id="sort-created_at-upcoming_ticket" class="px-6 py-3 text-left text-sm font-medium text-gray-800 uppercase tracking-wider border-b">
                    Action <span class="sort-arrow"></span>
                </th>
            </tr>
        </thead>
        <tbody id="upcoming-tickets-body">
            <tr>
                <td class="px-6 py-4 text-gray-600" colspan="3">No completed tickets available.</td>
            </tr>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4 flex justify-center">
        <button id="previous-upcoming" class="px-4 py-2 bg-gray-600 text-white rounded-lg" disabled>Previous</button>
        <span id="upcoming_ticket-page-number" class="mx-4 text-gray-700">Page 1</span>
        <button id="next-upcoming" class="px-4 py-2 bg-gray-600 text-white rounded-lg">Next</button>
    </div>
</div>

<script>
    // Pagination for Completed Tickets (similar logic)
    let upcomingTicketsPage = 1;
    let upcomingTicketsPerPage = 20;  // Adjust per page as needed
    let totalUpcomingTickets = 1;

    let sortByUpcomingTickets = 'created_at'; // Default sort by 'completed_at'
    let sortOrderUpcomingTickets = 'desc'; // Default sort order 'desc'
    let searchQueryUpcomingTicket = ''
    
    function getUpcomingTickets(page = 1) {
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
            url: `{{ route('getUpcomingTickets', ['window_id' => $window->id]) }}?page=${page}&per_page=${upcomingTicketsPerPage}&sort_by=${sortByUpcomingTickets}&sort_order=${sortOrderUpcomingTickets}&search=${searchQueryUpcomingTicket}`,
            method: 'GET',
            success: function(response) {
                if (response.success && response.tickets.length > 0) {
                    totalUpcomingTickets = response.total_pages;
                    $('#upcoming-tickets-body').html(
                        response.tickets.map(ticket =>
                            `<tr>
                                <td class="px-6 py-4 text-gray-600">${ticket.code}</td>
                                <td class="px-6 py-4 text-gray-600">${ticket.ticket_number}</td>
                                <td class="px-6 py-4 text-gray-600">${formatDate(ticket.created_at)}</td>
                                <td class="px-6 py-4 text-gray-600 text-center">
                                    <button 
                                        onclick="handleUpcomingTicket(${ticket.id})"
                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-md border border-gray-400">
                                        Handle Ticket
                                    </button>
                                </td>
                            </tr>`
                        ).join('')
                    );
                    $('#upcoming_ticket-page-number').text(`Page ${page}`);
                    $('#previous-upcoming').prop('disabled', page === 1);
                    $('#next-upcoming').prop('disabled', page === totalUpcomingTickets);
                } else {
                    $('#upcoming-tickets-body').html(
                        `<tr>
                            <td class="px-6 py-4 text-gray-600" colspan="3">No completed tickets available.</td>
                        </tr>`
                    );
                }

                console.log(response)
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Error while fetching completed tickets");
            }
        });
    }

    // Sorting for completed tickets
    $('#sort-created_at-upcoming_ticket').on('click', function() {
        // Toggle sorting order
        sortByUpcomingTickets = 'completed_at';
        sortOrderUpcomingTickets = (sortOrderUpcomingTickets === 'asc') ? 'desc' : 'asc';
        // Toggle arrow classes
        toggleSortArrowUpcomingTickets($(this), sortOrderUpcomingTickets);
        getCompletedTickets(upcomingTicketsPage); // Fetch sorted data
    });

    $('#sort-ticket_number-upcoming_ticket').on('click', function() {
        // Toggle sorting order
        sortByUpcomingTickets = 'ticket_number';
        sortOrderUpcomingTickets = (sortOrderUpcomingTickets === 'asc') ? 'desc' : 'asc';
        // Toggle arrow classes
        toggleSortArrowUpcomingTickets($(this), sortOrderUpcomingTickets);
        getCompletedTickets(upcomingTicketsPage); // Fetch sorted data
    });

    function toggleSortArrowUpcomingTickets(element, order) {
        // Reset arrow for all headers
        $('#sort-created_at-upcoming_ticket').removeClass('sort-asc sort-desc');
        $('#sort-ticket_number-upcoming_ticket').removeClass('sort-asc sort-desc');

        // Apply appropriate class based on order
        if (order === 'asc') {
            element.addClass('sort-asc');
        } else {
            element.addClass('sort-desc');
        }
    }

    $('#previous-upcoming').on('click', function() {
        if (upcomingTicketsPage > 1) {
            upcomingTicketsPage--;
            getCompletedTickets(upcomingTicketsPage);
        }
    });

    $('#next-upcoming').on('click', function() {
        if (upcomingTicketsPage < totalUpcomingTickets) {
            upcomingTicketsPage++;
            getCompletedTickets(upcomingTicketsPage);
        }
    });

    $('#search-upcoming').on('input', function() {
        searchQueryUpcomingTicket = $(this).val(); // Get the search query
        upcomingTicketsPage = 1; // Reset to the first page when searching
        get(onHoldPage); // Fetch tickets based on search query
    });

    function handleUpcomingTicket(ticketId) {
        // Construct the URL with a placeholder
        const url = `{{ route('handleTicket', ['window_id' => $window->id, 'ticket_id' => '__TICKET_ID__']) }}`;

        // Replace the placeholder with the actual ticketId
        const finalUrl = url.replace('__TICKET_ID__', ticketId);

        $.ajax({
            url: finalUrl,
            method: 'GET',
            success: function(response) {
                console.log(response);
                if (response.success) {
                    location.reload();
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
    }
</script>
