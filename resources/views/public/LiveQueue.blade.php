<x-App>
  <x-slot name="content">
    <div
      class="flex flex-wrap bg-[url('https://th.bing.com/th/id/OIP.CyyzDsXdZ2mk3HbUCv4THQHaEK?rs=1&pid=ImgDetMain')]">
      <div class="w-1/3">
        <div class="grid grid-cols-1 gap-6 p-5 h-full">
          <!-- Window Groups Section -->
          <div id="windows-container"
            class="p-6 bg-white border border-gray-300 rounded-lg shadow-2xl flex flex-col h-full">
            <div id="window-groups-placeholder" class="text-lg text-gray-600">
              Loading window groups...
            </div>
          </div>
        </div>
      </div>

      <div class="w-2/3">
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
                                  <h3 class="text-base font-medium text-gray-700 flex items-center mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                      <path fill-rule="evenodd" d="M9 3a7 7 0 1114 14A7 7 0 019 3zm0 1a6 6 0 10-.001 12.001A6 6 0 009 4z" clip-rule="evenodd" />
                                    </svg>
                                    ${window.name}
                                  </h3>
                                  ${window.issuedTickets && window.issuedTickets.length > 0
                  ? `<div class="overflow-hidden relative" style="max-height: 300px;">
                                            <ul class="mt-4 pl-0 space-y-4 auto-scroll">
                                              ${window.issuedTickets
                    .map(ticket => `
                                                  <li>
                                                    <div class="relative bg-white border border-green-200 rounded-lg shadow p-4 flex flex-col min-h-[80px]">
                                                      <div class="text-3xl font-extrabold text-green-700">${ticket.code}</div>
                                                      <div class="absolute bottom-2 right-4 text-sm text-gray-500">Head to ${window.window_name}</div>
                                                    </div>
                                                  </li>
                                                `)
                    .join('')}
                                            </ul>
                                        </div>`
                  : `<p class="text-lg text-gray-600">Waiting</p>`
                }
                                </div>
                                `
              windowsContainer.append(windowHtml);

            });
          } else {
            windowsContainer.text('No window groups found.');
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



  Echo.channel('live-queue.{{ $queue->id }}')
    .listen('CallingTicket', (e) => {
      setTimeout(() => {
        const videos = document.querySelectorAll('video.carousel-media');
        const originalVolumes = [];

        // Lower volume of all videos
        videos.forEach((video, i) => {
          originalVolumes[i] = video.volume;
          video.volume = 0.1;  // or any low volume you prefer
        });

        function textToSpeech(text) {
          if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';

            utterance.onend = () => {
              // Restore original volumes when speech ends
              videos.forEach((video, i) => {
                video.volume = originalVolumes[i];
              });
            };

            speechSynthesis.speak(utterance);
          } else {
            console.error('Text-to-speech is not supported in this browser.');
            // Restore volumes if TTS not supported
            videos.forEach((video, i) => {
              video.volume = originalVolumes[i];
            });
          }
        }

        textToSpeech('Calling Ticket Number ' + e.ticketNumber + ' to go to ' + e.windowName);

      }, 2000);
    });
  });



</script>