<x-App>
    <x-slot name="content">
        <div class="flex flex-wrap bg-[url('https://th.bing.com/th/id/OIP.CyyzDsXdZ2mk3HbUCv4THQHaEK?rs=1&pid=ImgDetMain')]"
        >
        <div class="w-2/3">
          <div class="grid grid-cols-1 gap-6 p-5 h-full">
            <!-- Window Groups Section -->
            <div id="windows-container" class="p-6 bg-white border border-gray-300 rounded-lg shadow-2xl flex flex-col h-full">
              <h2 class="text-3xl font-semibold text-gray-900 mb-6 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Calling
              </h2>
              <div id="window-groups-placeholder" class="text-lg text-gray-600">
                Loading window groups...
              </div>
            </div>
            <!-- Queued Users Section -->
            <div id="tickets-container" class="p-6 bg-white border border-gray-300 rounded-lg shadow-2xl flex flex-col w-12/12 h-full">
              <h2 class="text-3xl font-semibold text-gray-900 mb-6 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0 1a9 9 0 110-18 9 9 0 010 18z" clip-rule="evenodd" />
                </svg>
                Please prepare
              </h2>
              <div id="queued-users-placeholder" class="text-lg text-gray-600">
                Loading queued users...
              </div>
            </div>
          </div>
        </div>
        
            <div class="w-1/3">
              <x-Carousel :queue="$queue"></x-Carousel>
            </div>
        </div>          
    </x-slot>
</x-App>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        function getLiveData() {
          $.ajax({
              url: "{{ route('getLiveData', ['id' => $queue->id]) }}",
              method: 'GET',
              success: function (response) {
                  // Populate Window Groups
                  const windowsContainer = $('#window-groups-placeholder');
                  windowsContainer.empty(); // Clear existing content
                  if (response.windows && response.windows.length > 0) {
                      response.windows.forEach(window => {
                          const windowHtml = `
                              <div class="mb-6 p-5 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                                  <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                          <path fill-rule="evenodd" d="M9 3a7 7 0 1114 14A7 7 0 019 3zm0 1a6 6 0 10-.001 12.001A6 6 0 009 4z" clip-rule="evenodd" />
                                      </svg>
                                      ${window.name}
                                  </h3>
                                  ${
                                      window.issuedTickets && window.issuedTickets.length > 0
                                          ? `<ul class="mt-4 pl-6 list-disc text-lg text-gray-700 space-y-2">
                                              ${window.issuedTickets
                                                  .map(ticket => `<li>${ticket.code} - Head to ${window.window_name}</li>`)
                                                  .join('')}
                                            </ul>`
                                          : `<p class="text-lg text-gray-600">Waiting</p>`
                                  }
                              </div>`;
                          windowsContainer.append(windowHtml);
                          
                      });
                  } else {
                      windowsContainer.text('No window groups found.');
                  }

                  // Populate Queued Users
                  const ticketsContainer = $('#queued-users-placeholder');
                  ticketsContainer.empty(); 

                  if (response.ticketsOldest && response.ticketsOldest.length > 0) {
                      const ticketsHtml = `
                          <ul class="pl-6 list-disc text-lg text-gray-700 space-y-2">
                              ${response.ticketsOldest
                                  .map(ticket => `<li>${ticket.code}</li>`)
                                  .join('')}
                          </ul>`;
                      ticketsContainer.append(ticketsHtml);
                  } else {
                      ticketsContainer.text('Queue Empty');
                  }
              },
              error: function (xhr, status, error) {
                  console.error('Error:', error);
                  alert('An error occurred while fetching data.');
              }
          });
        }

      getLiveData();

      Echo.channel('live-queue.{{$queue->id}}')
      .listen('DashboardEvent', () => {
          console.log("A Window event has been detected");
          
          console.log("The event has been received");
          // Add a timeout before calling getLiveData
          setTimeout(() => {
              getLiveData();
          }, 2000); // 2000ms = 2 seconds
      });

      Echo.channel('live-queue.{{$queue->id}}')
      .listen('NewTicketEvent', () => {
          console.log("A Ticket event has been detected");
          
          // Add a timeout before calling getLiveData
          setTimeout(() => {
              getLiveData();
          }, 2000); // 2000ms = 2 seconds
      });

    });
  </script>