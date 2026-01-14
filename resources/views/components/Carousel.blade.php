<style>
  /* Overlay prompt */
  #startPrompt {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.75);
    color: white;
    font-size: 2rem;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    cursor: pointer;
  }

  .card {
    border-radius: 20px;
    box-shadow: 15px 15px 15px rgb(67, 116, 29),
                -10px -10px 15px rgba(110, 181, 24, 0.593);
  }

  /* Global gradient overlay (one only) */
  .gradient-overlay {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 10;
    border-radius: 0.5rem;
    background-blend-mode: normal;
  }
</style>
<!-- Single Gradient Overlay (global) -->
<div class="gradient-overlay rounded-lg"></div>
<!-- Start Prompt Overlay -->
<div id="startPrompt">Click or tap anywhere to start</div>

<!-- Carousel Container -->
<div class=" w-full overflow-hidden rounded-lg card bg-black bg-opacity-80 h-full" >
  <!-- Carousel wrapper -->
  <div class="carousel-container  z-0 flex transition-transform duration-500 ease-in-out w-full h-full">

    @php
      $mediaAds = json_decode($queue->media_advertisement ?? '[]', true) ?? [];
    @endphp

    @if (!empty($mediaAds))
      @foreach ($mediaAds as $mediaPath)
        @php
          $extension = strtolower(pathinfo($mediaPath, PATHINFO_EXTENSION));
        @endphp

        <div class="carousel-item  flex-shrink-0 w-full h-full rounded-lg">
          @if (in_array($extension, ['mp4', 'webm', 'ogg']))
            <video
              class="block w-full h-full object-contain carousel-media"
              src="{{ asset('storage/' . $mediaPath) }}"
              controls
              muted
              playsinline
              preload="metadata">
            </video>
          @else
            <img
              src="{{ asset('storage/' . $mediaPath) }}"
              alt="Advertisement"
              class="block w-full h-full object-contain carousel-media rounded-lg" />
          @endif
        </div>
      @endforeach
    @else
      <div class="carousel-item relative flex-shrink-0 w-full h-full">
        <img
          src="https://via.placeholder.com/800x600?text=No+Advertisement"
          alt="No Advertisement"
          class="block w-full h-full object-cover carousel-media rounded-lg" />
      </div>
    @endif

  </div>
</div>


<!-- Carousel Script -->
<script>
  const carouselContainer = document.querySelector('.carousel-container');
  const carouselItems = document.querySelectorAll('.carousel-item');
  const mediaElements = document.querySelectorAll('.carousel-media');
  const startPrompt = document.getElementById('startPrompt');

  let activeIndex = 0;
  let carouselStarted = false;
  let timeoutId = 1000;

  function goToSlide(index) {
    const itemWidth = carouselItems[0].offsetWidth;
    carouselContainer.style.transform = `translateX(${-index * itemWidth}px)`;
  }

  function playNextMedia() {
    clearTimeout(timeoutId);
    const currentMedia = mediaElements[activeIndex];

    if (currentMedia.tagName.toLowerCase() === 'video') {
      currentMedia.muted = false;
      currentMedia.currentTime = 0;

      currentMedia.play().then(() => {
        currentMedia.onended = () => {
          activeIndex = (activeIndex + 1) % carouselItems.length;
          goToSlide(activeIndex);
          playNextMedia();
        };
      }).catch(() => {
        timeoutId = setTimeout(() => {
          activeIndex = (activeIndex + 1) % carouselItems.length;
          goToSlide(activeIndex);
          playNextMedia();
        }, 1000);
      });
    } else {
      timeoutId = setTimeout(() => {
        activeIndex = (activeIndex + 1) % carouselItems.length;
        goToSlide(activeIndex);
        playNextMedia();
      }, 7500);
    }
  }

  function startCarousel() {
    if (carouselStarted) return;
    carouselStarted = true;
    startPrompt.style.display = 'none';
    goToSlide(activeIndex);
    playNextMedia();
    document.removeEventListener('click', startCarousel);
  }

  document.addEventListener('click', startCarousel);
  window.addEventListener('resize', () => goToSlide(activeIndex));
</script>
