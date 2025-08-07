<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/Ticketing.blade.php -->
<x-App>
    <x-slot name="content">
        <div class="justify-center flex px-12 py-6 bg-gray-300 min-h-screen">
        {{-- <div class="flex flex-col items-center justify-center w-full max-w-4xl mx-auto">
           
        </div> --}}

        <div class=" flex items-center justify-center lg:px-0" style="width: 100%">
            <div class="border border-gray-200 rounded-xl bg-white shadow-lg w-full xl:w-1/2 p-12">
                
            <h1 class="text-4xl sm:text-5xl font-black text-green-500 text-center mb-3 tracking-tight">
            Claim Your Queue Ticket
            </h1>
            <p class="text-base sm:text-lg text-gray-800 dark:text-gray-800 text-center max-w-2xl mx-auto mb-8">
            You're joining <span class="font-semibold text-gray-800">{{ $queue->name }}</span> — let's make this smooth and easy!
            </p>

                <div>
                    @if ($queue->windows->isNotEmpty())
                        <form action="{{ route('ticketing.submit') }}" method="POST" class="space-y-8">
                            @csrf
                            <input type="hidden" name="queue_id" value="{{ $queue->id }}">
                            <fieldset>
                                <legend class="block text-xl lg:text-2xl font-medium text-gray-800 mb-4 text-center"> <strong>Click</strong> here to Choose where to queue:</legend>
                                
                                <div class="flex flex-wrap gap-6 justify-center w-100%">
                                    @foreach ($queue->windows as $window)
                                        <div class="flex items-center w-1/4 window-selection-item" data-description="{{ $window->description }}">
                                            <input id="windows_group_{{ $window->id }}" name="window_id" type="radio" value="{{ $window->id }}" class="hidden peer"  {{ $window->status === 'closed' ? 'disabled' : '' }}>
                                            <label for="windows_group_{{ $window->id }}" 
                                                class="peer-checked:bg-green-700 peer-checked:text-white peer-checked:border-green-900 peer-checked:shadow-md 
                                                border border-gray-300 hover:border-gray-400
                                                transition-all cursor-pointer flex items-center justify-center w-full h-40 px-8 py-6 {{ $window->status === 'closed' ? 'bg-gray-200 border-gray-400 text-gray-500 cursor-not-allowed' : 'bg-gray-100 border-gray-300 text-gray-800 hover:bg-gray-200' }} text-3xl font-bold rounded-lg">
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
                            <div id="description" class="mt-4 p-4 bg-green-200 border border-gray-300 rounded-lg text-gray-600 text-lg">
                                <strong>Hint:</strong> Click an item above to see details here.
                            </div>

                            <!-- Name Input -->
                            <div>
                                <label for="name" class="block text-lg font-medium text-gray-800 mb-2">Student Name</label>
                                <input type="text" id="name" name="name" required placeholder="e.g., Mark Vincent" class="mt-1 block w-full px-4 py-3 border border-gray-800 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-800">
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-center">
                                <button type="submit" class="w-full lg:w-auto px-8 py-4 bg-green-900 hover:margin-bottom-6 transition-all text-white text-lg font-bold rounded-lg shadow hover:bg-indigo-600 transition-all">
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
        const $radioButtons =$(".window-selection-item")
        const descriptionDiv = document.getElementById('description');

        $radioButtons.on('mousedown', function () {
            const description = this.getAttribute('data-description');

            descriptionDiv.innerHTML = `<strong>Description:</strong> ${description || 'No description available.'}`;
            descriptionDiv.classList.add('bg-indigo-50', 'border-indigo-300');
    
            
            Echo.channel('live-queue.{{$queue->id}}')
            .listen('QueueSettingsChanged', () => {
                
                //Refresh page just in case admin changes window open/close or other data
                setTimeout(() => {
                    location.reload()
                }, 5000); // 2000ms = 2 seconds
            });
        });
    });
</script>