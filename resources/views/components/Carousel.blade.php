
<div class="relative h-screen bg-white shadow-lg overflow-hidden rounded-lg shadow-inner" data-twe-carousel-init>
  <!-- Carousel wrapper -->
  <div class="carousel-container relative flex transition-transform duration-500 ease-in-out w-full h-full" style="transform: translateX(0);">
    @php
        $mediaAds = json_decode($queue->media_advertisement ?? '[]', true) ?? [];
    @endphp

    @if (!empty($mediaAds))
        @foreach ($mediaAds as $mediaPath)
            @if (!empty($mediaPath))
                <div class="carousel-item relative flex-shrink-0 w-full h-full">
                    <img 
                      src="{{ asset('storage/' . $mediaPath) }}" 
                      alt="Advertisement Image" 
                      class="block w-full h-full object-cover" 
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                </div>
            @endif
        @endforeach
    @else
        <!-- Optional fallback if no images -->
        <div class="carousel-item relative flex-shrink-0 w-full h-full">
          <img 
            src="https://via.placeholder.com/800x600?text=No+Advertisement" 
            alt="No Advertisement" 
            class="block w-full h-full object-cover" 
          />
          <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
        </div>
    @endif
  </div>
</div>


  <script>
      const carouselContainer = document.querySelector('.carousel-container');
    const carouselItems = document.querySelectorAll('.carousel-item');
    const itemWidth = carouselItems[0].offsetWidth; // Width of a single item
    let activeIndex = 0;
    const intervalTime = 3000; // 3 seconds interval to switch to the next image

    function swipeToNext() {
      // Update active index
      activeIndex = (activeIndex + 1) % carouselItems.length;

      // Calculate new translateX value
      const translateX = -activeIndex * itemWidth;

      // Apply the transform to swipe
      carouselContainer.style.transform = `translateX(${translateX}px)`;
    }

    // Set interval for the swipe effect
    setInterval(swipeToNext, intervalTime);

    // Ensure correct dimensions are applied on window resize
    window.addEventListener('resize', () => {
      const newWidth = carouselItems[0].offsetWidth;
      const translateX = -activeIndex * newWidth;
      carouselContainer.style.transform = `translateX(${translateX}px)`;
    });
  </script>