<style>
  /* Fullscreen overlay to prompt user interaction */
  #startPrompt {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.75);
    color: white;
    font-size: 2rem;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    cursor: pointer;
  }
</style>

<div id="startPrompt">
  Click or tap anywhere to start
</div>

<div class="relative h-screen bg-white shadow-lg overflow-hidden rounded-lg shadow-inner" data-twe-carousel-init>
  <!-- Carousel wrapper -->
  <div class="carousel-container relative flex transition-transform duration-500 ease-in-out w-full h-full" style="transform: translateX(0);">
    @php
        $mediaAds = json_decode($queue->media_advertisement ?? '[]', true) ?? [];
    @endphp

    @if (!empty($mediaAds))
        @foreach ($mediaAds as $mediaPath)
            @php
                $extension = pathinfo($mediaPath, PATHINFO_EXTENSION);
            @endphp

            <div class="carousel-item relative flex-shrink-0 w-full h-full">
                @if (in_array(strtolower($extension), ['mp4', 'webm', 'ogg']))
                  <video 
                    class="block w-full h-full object-cover carousel-media" 
                    src="{{ asset('storage/' . $mediaPath) }}" 
                    controls 
                    muted 
                    playsinline 
                    preload="metadata"
                  ></video>
                @else
                    <img 
                        src="{{ asset('storage/' . $mediaPath) }}" 
                        alt="Advertisement" 
                        class="block w-full h-full object-cover carousel-media" 
                    />
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
            </div>
        @endforeach
    @else
        <div class="carousel-item relative flex-shrink-0 w-full h-full">
            <img 
                src="https://via.placeholder.com/800x600?text=No+Advertisement" 
                alt="No Advertisement" 
                class="block w-full h-full object-cover carousel-media" 
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
        </div>
    @endif
  </div>
</div>

<script>
  const carouselContainer = document.querySelector('.carousel-container');
  const carouselItems = document.querySelectorAll('.carousel-item');
  const mediaElements = document.querySelectorAll('.carousel-media');
  const startPrompt = document.getElementById('startPrompt');
  let activeIndex = 0;
  let carouselStarted = false;
  let timeoutId;

  function goToSlide(index) {
    const itemWidth = carouselItems[0].offsetWidth;
    const translateX = -index * itemWidth;
    carouselContainer.style.transform = `translateX(${translateX}px)`;
  }

  function playNextMedia() {
    clearTimeout(timeoutId);
    const currentMedia = mediaElements[activeIndex];

    if (currentMedia.tagName.toLowerCase() === 'video') {
      currentMedia.muted = false;  // unmute video after interaction
      currentMedia.currentTime = 0;

      // Play video and wait for end
      currentMedia.play().then(() => {
        currentMedia.onended = () => {
          activeIndex = (activeIndex + 1) % carouselItems.length;
          goToSlide(activeIndex);
          playNextMedia();
        };
      }).catch(err => {
        console.error('Video playback failed:', err);
        // If video play fails, fallback to next slide after 10s
        timeoutId = setTimeout(() => {
          activeIndex = (activeIndex + 1) % carouselItems.length;
          goToSlide(activeIndex);
          playNextMedia();
        }, 10000);
      });
    } else {
      // Image - show for 10 seconds then next
      timeoutId = setTimeout(() => {
        activeIndex = (activeIndex + 1) % carouselItems.length;
        goToSlide(activeIndex);
        playNextMedia();
      }, 10000);
    }
  }

  function startCarousel() {
    if (carouselStarted) return; // Prevent multiple starts
    carouselStarted = true;

    // Hide the start prompt overlay
    startPrompt.style.display = 'none';

    goToSlide(activeIndex);
    playNextMedia();

    // Remove listener after first interaction
    document.removeEventListener('click', startCarousel);
  }

  // Listen for any user interaction on the document to start
  document.addEventListener('click', startCarousel);

  // Resize correction
  window.addEventListener('resize', () => {
    goToSlide(activeIndex);
  });

</script>
