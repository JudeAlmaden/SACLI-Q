<x-App>


  <x-slot name="content">
    <style>
      #logo {
        position: absolute !important;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 80%;
        max-height: 80%;
        pointer-events: none;
        /* so it doesn't block clicks */
        z-index: 0;
        opacity: 0.15;
        mix-blend-mode: multiply; /* Blend the image with the background to remove white */
      }

      #windows-container {
        position: relative;
        overflow: hidden;
        background-color: rgba(255, 255, 255, 0.0); /* white with 50% transparency */
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
      }

      .circles li {
        position: absolute;
        display: block;
        list-style: none;
        width: 20px;
        height: 20px;
        background: rgba(255, 255, 255, 0.2);
        animation: animate 25s linear infinite;
        left: -150px;
      }

      .circles li:nth-child(1) {
        top: 25%;
        width: 80px;
        height: 80px;
        animation-delay: 0s;
      }

      .circles li:nth-child(2) {
        top: 10%;
        width: 20px;
        height: 20px;
        animation-delay: 2s;
        animation-duration: 12s;
      }

      .circles li:nth-child(3) {
        top: 70%;
        width: 20px;
        height: 20px;
        animation-delay: 4s;
      }

      .circles li:nth-child(4) {
        top: 40%;
        width: 60px;
        height: 60px;
        animation-delay: 0s;
        animation-duration: 18s;
      }

      .circles li:nth-child(5) {
        top: 65%;
        width: 20px;
        height: 20px;
        animation-delay: 0s;
      }

      .circles li:nth-child(6) {
        top: 75%;
        width: 110px;
        height: 110px;
        animation-delay: 3s;
      }

      .circles li:nth-child(7) {
        top: 35%;
        width: 150px;
        height: 150px;
        animation-delay: 7s;
      }

      .circles li:nth-child(8) {
        top: 50%;
        width: 25px;
        height: 25px;
        animation-delay: 15s;
        animation-duration: 45s;
      }

      .circles li:nth-child(9) {
        top: 20%;
        width: 15px;
        height: 15px;
        animation-delay: 2s;
        animation-duration: 35s;
      }

      .circles li:nth-child(10) {
        top: 85%;
        width: 150px;
        height: 150px;
        animation-delay: 0s;
        animation-duration: 11s;
      }

      @keyframes animate {
        0% {
          transform: translateX(0) rotate(0deg);
          opacity: 1;
          border-radius: 0;
        }
        100% {
          transform: translateX(1000px) rotate(720deg);
          opacity: 0.8; /* Increase opacity to make circles more noticeable */
          border-radius: 50%;
        }
      }
    </style>

    <!--Background Content-->
    <div class="context">
      <div class="area absolute top-0">
        <ul class="circles">
          <li></li>
          <li></li>
          <li></li>
          <li></li>
          <li></li>
          <li></li>
          <li></li>
          <li></li>
          <li></li>
          <li></li>
        </ul>
      </div>
    </div>

    <!--Main Content-->
    <div class="flex flex-wrap ">
      <div class="w-1/3">
        <div class="grid grid-cols-1 gap-6 p-5 h-full">
          <!-- Window Groups Section -->
          <div id="windows-container"
            class="p-6 rounded-lg shadow-2xl flex flex-col h-full ">
            <div id="window-groups-list" class="text-lg text-gray-600">
              Loading window groups...
            </div>
            <img id="logo" src="{{ asset('/BSIT_Logo.jfif') }}" alt="Background" />
          </div>
        </div>
      </div>

      <div class="w-2/3">
        <x-Carousel :queue="$queue"></x-Carousel>
      </div>

      <div class="fixed bottom-0 left-0 w-full">
        <div class="bg-black bg-opacity-50 text-white p-4 text-center">
          Check your ticket at <a href="{{ route('info') }}"
            class="text-blue-400 underline hover:text-blue-600 transition">{{ route('info') }}</a> to see your current
          status.
        </div>
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
            response.windows.forEach(window => {
              const windowHtml = `
                                <div class="mb-6 p-5 border border-gray-200 rounded-lg shadow-sm bg-gray-50 shadow-xl">
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

          // Usage
          speakTicketNumber(e.ticketNumber);

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

    async function speakTicketNumber(ticketNumber) {
      await playDingDong();

      const msg = new SpeechSynthesisUtterance();
      msg.text = `Calling ticket number ${ticketNumber}`;
      const voices = window.speechSynthesis.getVoices();
      msg.voice = voices.find(voice => voice.lang === 'en-US' && voice.name.includes('Google')) || voices[0];
      msg.rate = 0.9;
      msg.pitch = 1.1;
      msg.volume = 1;
      window.speechSynthesis.speak(msg);
    }

  });

</script>