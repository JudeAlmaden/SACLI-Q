<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/Ticketing.blade.php -->
<x-App>
    <x-slot name="content">
        <div class="bg-gray-50 min-h-screen flex items-center justify-center px-6 lg:px-0">
            <div class="p-12 border border-gray-200 rounded-xl bg-white shadow-lg w-full lg:w-1/2">
                <h1 class="text-5xl font-extrabold text-green-400 text-center mb-4"> Get Your Ticket</h1>
                <p class="text-lg text-gray-600 text-center mb-8">
                    Welcome to the {{ $queue->name }}! Let’s make your experience hassle-free.
                </p>
                
                <div>
                    @if ($queue->windows->isNotEmpty())
                        <form action="{{ route('ticketing.submit') }}" method="POST" class="space-y-8">
                            @csrf
                            <input type="hidden" name="queue_id" value="{{ $queue->id }}">
                            <fieldset>
                                <legend class="block text-xl lg:text-2xl font-medium text-gray-800 mb-4 text-center">Choose where to queue:</legend>
                                <div class="flex flex-wrap gap-6 justify-center">
                                    @foreach ($queue->windows as $window)
                                        <div class="flex items-center w-full md:w-1/4">
                                            <input id="windows_group_{{ $window->id }}" name="window_id" type="radio" value="{{ $window->id }}" class="hidden peer" data-description="{{ $window->description }}" {{ $window->status === 'closed' ? 'disabled' : '' }}>
                                            <label for="windows_group_{{ $window->id }}" 
                                                   class="peer-checked:bg-indigo-100 peer-checked:border-indigo-600 peer-checked:shadow-md transition-all cursor-pointer flex items-center justify-center w-full h-40 px-8 py-6 {{ $window->status === 'closed' ? 'bg-gray-200 border-gray-400 text-gray-500 cursor-not-allowed' : 'bg-gray-100 border-gray-300 text-gray-800 hover:bg-gray-200' }} text-3xl font-bold rounded-lg">
                                                {{ $window->name }}
                                                @if ($window->status === 'closed')
                                                    <span class="block text-sm text-red-500 mt-2">Not Available</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                
                            </fieldset>

                            <!-- Description -->
                            <div id="description" class="mt-4 p-4 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 text-lg">
                                <strong>Hint:</strong> Select an item above to see details here.
                            </div>

                            <!-- Name Input -->
                            <div>
                                <label for="name" class="block text-lg font-medium text-gray-800 mb-2">Your Name (Optional)</label>
                                <input type="text" id="name" name="name" placeholder="e.g., Jane Doe" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-800">
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-center">
                                <button type="submit" class="w-full lg:w-auto px-8 py-4 bg-green-500 text-white text-lg font-bold rounded-lg shadow hover:bg-indigo-600 transition-all">
                                    Get My Ticket
                                </button>
                            </div>
                        </form>
                    @else
                        <p class="text-xl text-gray-600 text-center">
                            Oops! It looks like there are no groups available right now. Please check back later.
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <x-ErrorAlert></x-ErrorAlert>
    </x-slot>
</x-App>

<style>
    input[type="radio"]:checked + label {
        background-color: #e0e7ff; /* Indigo-100 */
        border-color: #4f46e5; /* Indigo-600 */
        color: #1e293b; /* Gray-800 */
    }
    label {
        transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    }
    label:hover {
        transform: scale(1.02);
        background-color: #f3f4f6; /* Gray-200 */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radioButtons = document.querySelectorAll('input[name="windows_group"]');
        const descriptionDiv = document.getElementById('description');

        // Update description on radio button change
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function () {
                const description = this.getAttribute('data-description');
                descriptionDiv.innerHTML = `<strong>Description:</strong> ${description || 'No description available.'}`;
                descriptionDiv.classList.add('bg-indigo-50', 'border-indigo-300');
            });
        });
        
        Echo.channel('live-queue.{{$queue->id}}')
      .listen('QueueSettingsChanged', () => {
          
          //Refresh page just in case admin changes window open/close or other data
          setTimeout(() => {
              location.reload()
          }, 5000); // 2000ms = 2 seconds
      });
    });
</script>