<x-App>
  <x-slot name="content">
    <style>
      #windows-container {
        position: relative;
        overflow: hidden;
        background-color: rgba(255, 255, 255, 0);
      }

      #window-groups-list {
        position: relative;
        z-index: 1;
      }

      .context {
        width: 100%;
        top: 50vh;
      }

      .area {
        background: #a8e063;
        background: -webkit-linear-gradient(to left, #f7ff00, #a8e063);
        width: 100%;
        height: 100vh;
      }

      .circles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
      }

      .circles li {
        position: absolute;
        display: block;
        list-style: none;
        background: rgba(255, 255, 100, 0.8);
        animation: animate 30s linear infinite;
        bottom: -150px;
      }

      @keyframes animate {
        0% {
          transform: translate(0, 0) rotate(0deg);
          opacity: 1;
        }

        100% {
          transform: translate(-1000px, -1000px) rotate(720deg);
          opacity: 0.6;
        }
      }
    </style>



    <!--Background Content-->
    <div class="context">
      <div class="area absolute top-0">
        <ul class="circles">
          @for ($i = 0; $i < 30; $i++)
          @php
        $size = rand(15, 100);
        $duration = rand(10, 45);
        $delay = rand(0, 20);
        $right = rand(0, 95);
        $rounded = rand(0, 10) < 3 ? 'border-radius: 10px;' : ''; // 30% chance to be rounded square
      @endphp
          <li style="
          width: {{ $size }}px;
          height: {{ $size }}px;
          animation-delay: {{ $delay }}s;
          animation-duration: {{ $duration }}s;
          right: {{ $right }}%;
          {{ $rounded }}
        "></li>
      @endfor
        </ul>
      </div>
    </div>

    <!--Main Content-->
    <div class="flex flex-wrap ">
      <div class="w-1/3">
        <div class="grid grid-cols-1 gap-6 p-5 h-full relative">
          <!-- Window Groups Section -->
          <div id="windows-container" class="p-6 rounded-lg shadow-2xl flex flex-col h-full relative card">
            {{-- Header --}}
            <div class="w-full flex items-center justify-between  bg-transparent">
              <!-- Left Logo -->
              <img src="/sacli_logo-remove-bg.png" alt="SACLI Logo" class="h-20 object-contain" />

              <!-- Center Text -->
              <div class="flex flex-col items-center text-gray-900">
                <span class="text-xl font-bold tracking-wide text-center uppercase">
                  Developed By
                </span>
                <span class="text-lg font-medium tracking-wider text-center text-gray-900">
                  Bachelor of Science in Information Technology
                </span>
              </div>

              <!-- Right Logo -->
              <img src="/jpcs_logo-remove-bg.png" alt="JPCS Logo" class="h-20 object-contain" />
            </div>

            <hr class="my-5">

            {{-- Live --}}
            <div id="window-groups-list" class="text-lg text-gray-600 mt-5">
              Loading window groups...
            </div>

            {{-- Footer --}}
            <div class="absolute bottom-0 left-0 w-full z-20">
              <div class="bg-black  text-white p-4 text-center rounded-lg">
                Check your ticket at
                <a href="{{ route('info') }}" class="text-blue-400 underline hover:text-blue-600 transition">
                  {{ route('info') }}
                </a>
                to see your current status.
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="w-2/3 relative p-5 h-screen">
        <!-- Notification inside parent -->

        <!-- Carousel -->
        <x-Carousel :queue="$queue" />
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
          const windowsContainer = $('#window-groups-list');
          windowsContainer.empty(); // Clear existing content
          if (response.windows && response.windows.length > 0) {
            console.log(response)
            response.windows.forEach(window => {
              const windowHtml = `
                                <div class="mb-6 p-5 border border-gray-200 rounded-lg shadow-sm bg-gray-50 shadow-xl ">
                                  <h3 class="text-base font-medium text-gray-700 flex items-center mb-2 ">
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
                                                    <div class="shadow-md relative bg-white border border-green-200 rounded-lg shadow p-4 flex flex-col min-h-[80px]">
                                                      <div class="text-3xl font-extrabold text-green-700">${ticket.code}</div>
                                                       <div class="absolute bottom-2 right-4 text-sm text-gray-500">${window.window_name}</div>
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


    // Play "ding-dong" sound before speaking ticket number
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

                // Usage
          speakTicketNumber(e.ticketNumber, e.windowName)
            .then(() => {
              // Restore original volumes after speaking
              videos.forEach((video, i) => {
                video.volume = originalVolumes[i];
              });
            })
            .catch(error => {
              console.error("Error speaking ticket number:", error);
              // Restore original volumes in case of error
              videos.forEach((video, i) => {
                video.volume = originalVolumes[i];
              });
            });

        }, 2000);
      });


    function playDingDong() {
      const audio = new Audio('{{ asset('storage/ding-dong-sfx.mp3') }}');
      audio.play();
      return new Promise(resolve => {
        audio.onended = resolve;
        // In case audio fails to play, resolve after 2 seconds
        audio.onerror = () => setTimeout(resolve, 2000);
      });
    }

    async function speakTicketNumber(ticketNumber, windowName) {
      await playDingDong();

      const msg = new SpeechSynthesisUtterance();
      msg.text = `Calling ticket number ${ticketNumber} to go to ${windowName}`;
      console.log("Speaking:", msg.text);
      const voices = window.speechSynthesis.getVoices();
      msg.voice = voices.find(voice => voice.lang === 'en-US' && voice.name.includes('Google')) || voices[0];
      msg.rate = 0.9;
      msg.pitch = 1.1;
      msg.volume = 1;
      window.speechSynthesis.speak(msg);
    }

  });

</script>