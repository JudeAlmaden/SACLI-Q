<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/Ticketing.blade.php -->
<x-App>
    <x-slot name="content">
        <!-- Main Container with Animated Background -->
        <div class="min-h-screen relative overflow-hidden bg-green-500 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
            
            <!-- Animated Background Blobs -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-green-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
                <div class="absolute top-0 right-1/4 w-96 h-96 bg-emerald-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
                <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-teal-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
            </div>

            <div class="relative z-10 sm:mx-auto sm:w-full sm:max-w-5xl" 
     x-data="{ show: false }" 
     x-init="setTimeout(() => show = true, 100)"
     x-show="show"
     x-transition:enter="transition ease-out duration-700"
     x-transition:enter-start="opacity-0 translate-y-10"
     x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-white/50 ring-1 ring-black/5">
                    <!-- Top Decorative Bar -->
                    <div class="h-2 bg-gradient-to-r from-green-400 via-lime-500 to-green-600"></div>

                    <div class="px-6 py-12 sm:px-12">
                        <!-- Header -->
                        <div class="text-center mb-10">
                            <h1 class="text-4xl font-black text-gray-900 tracking-tight sm:text-5xl mb-3 bg-clip-text text-transparent bg-gradient-to-r from-green-700 to-lime-600">
                                Claim Your Queue Ticket
                            </h1>
                            <p class="text-lg text-gray-600 font-medium max-w-2xl mx-auto">
                                Select a service window below and enter your details to join the queue.
                            </p>
                        </div>

                        <div>
                            @if ($queue->windows->isNotEmpty())
                                <form action="{{ route('ticketing.submit') }}" method="POST" class="space-y-8">
                                    @csrf
                                    <input type="hidden" name="queue_id" value="{{ $queue->id }}">
                                    
                                    <fieldset>
                                        <legend class="sr-only">Choose a window</legend>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                            @foreach ($queue->windows as $window)
                                                <div class="relative window-selection-item group" data-description="{{ $window->description }}">
                                                    <input id="windows_group_{{ $window->id }}" 
                                                           name="window_id" 
                                                           type="radio" 
                                                           value="{{ $window->id }}" 
                                                           class="peer sr-only" 
                                                           {{ $window->status === 'closed' ? 'disabled' : '' }}>
                                                    
                                                    <label for="windows_group_{{ $window->id }}" 
                                                        class="flex flex-col items-center justify-center p-6 h-full min-h-[160px] cursor-pointer rounded-2xl border-2 transition-all duration-300 ease-out transform
                                                               {{ $window->status === 'closed' 
                                                                  ? 'bg-gray-50 border-gray-200 opacity-60 grayscale cursor-not-allowed' 
                                                                  : 'bg-white border-gray-200 hover:border-green-400 hover:shadow-xl hover:-translate-y-1 hover:scale-105 peer-checked:bg-green-600 peer-checked:border-green-600 peer-checked:shadow-green-500/30 peer-checked:scale-[1.02]' 
                                                               }}">
                                                        
                                                        <!-- Icon Placeholder -->
                                                        <div class="mb-4 p-4 rounded-full {{ $window->status === 'closed' ? 'bg-gray-200 text-gray-400' : 'bg-green-100 text-green-600 peer-checked:bg-white/20 peer-checked:text-white group-hover:bg-green-500 group-hover:text-white' }} transition-colors duration-300">
                                                            @if($window->status === 'closed')
                                                                <i class="fa-solid fa-lock text-2xl"></i>
                                                            @else
                                                                <i class="fa-solid fa-users text-2xl"></i>
                                                            @endif
                                                        </div>

                                                        <span class="text-xl font-bold text-center {{ $window->status === 'closed' ? 'text-gray-500' : 'text-gray-900 peer-checked:text-white' }}">
                                                            {{ $window->name }}
                                                        </span>

                                                        @if ($window->status === 'closed')
                                                            <span class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 uppercase tracking-wide">
                                                                Unavailable
                                                            </span>
                                                        @else
                                                            <div class="mt-3 opacity-0 peer-checked:opacity-100 transition-all duration-300 transform peer-checked:translate-y-0 translate-y-2 text-white font-bold text-sm flex items-center gap-1.5">
                                                                <i class="fa-solid fa-circle-check"></i> Selected
                                                            </div>
                                                        @endif
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </fieldset>

                                    <!-- Context Area -->
                                    <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 shadow-sm transition-all duration-500 transform hover:shadow-md" id="description-container">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-shrink-0 mt-1">
                                                 <i class="fa-solid fa-circle-info text-blue-500 text-xl animate-pulse"></i>
                                            </div>
                                            <div id="description" class="text-gray-600 text-lg leading-relaxed">
                                                <span class="text-gray-400 italic">Select a window above to see details about who should queue there.</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Name Input -->
                                    <div class="space-y-4 pt-6 border-t border-gray-100">
                                        <label for="name" class="block text-lg font-bold text-gray-800">
                                            Your Name <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-green-500 text-gray-400">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                            <input type="text" 
                                                   id="name" 
                                                   autocomplete="off"
                                                   name="name" 
                                                   required 
                                                   placeholder="Enter your full name (e.g., Juan Dela Cruz)" 
                                                   class="block w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-lg placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-green-500/20 focus:border-green-500 transition-all shadow-sm focus:bg-white">
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="pt-4">
                                        <button type="submit" 
                                                class="group relative w-full flex justify-center py-4 px-6 border border-transparent text-lg font-bold rounded-xl text-white bg-gradient-to-r from-green-600 to-lime-600 hover:from-green-700 hover:to-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all shadow-lg hover:shadow-green-500/30 active:scale-[0.98] overflow-hidden">
                                            <span class="absolute top-0 left-0 w-full h-full bg-white/20 transform -translate-x-full skew-x-12 group-hover:animate-shimmer"></span>
                                            <span class="relative flex items-center gap-2">
                                                Get My Ticket <i class="fa-solid fa-ticket group-hover:rotate-12 transition-transform duration-300"></i>
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="text-center py-12">
                                    <div class="bg-gray-100 rounded-full h-24 w-24 flex items-center justify-center mx-auto mb-6 animate-pulse">
                                        <i class="fa-solid fa-store-slash text-gray-400 text-4xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Windows Available</h3>
                                    <p class="text-gray-500">
                                        There are currently no active windows for this queue. Please check back later.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="mt-8 text-center" 
                     x-show="show" 
                     x-transition:enter="transition ease-out duration-1000 delay-300" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100">
                    <p class="text-sm text-gray-800 font-bold">
                        &copy; {{ date('Y') }} SACLI Queueing System.
                    </p>
                </div>
            </div>
        </div>
        <x-ErrorAlert></x-ErrorAlert>
    </x-slot>
</x-App>


<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    @keyframes shimmer {
        100% { transform: translateX(100%) skewX(12deg); }
    }
    .group-hover\:animate-shimmer:hover {
        animation: shimmer 1s;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const $windowItems = $(".window-selection-item"); 
        const descriptionDiv = document.getElementById('description');
        const descriptionContainer = document.getElementById('description-container');
        const $nameInput = $("#name"); // Added for name input focus

        $windowItems.on('mousedown click', function () {
            const $input = $(this).find('input');
            if ($input.prop('disabled')) return;
            
            const description = $(this).attr('data-description');
            
            // Auto-focus the name input
            setTimeout(() => {
                $nameInput.focus();
            }, 50);

            // Smoother transition for content change
            descriptionDiv.style.opacity = '0';
            setTimeout(() => {
                if (description) {
                    descriptionDiv.innerHTML = `<span class="font-medium text-gray-900">Queue Purpose:</span> ${description}`;
                } else {
                     descriptionDiv.innerHTML = `<span class="text-gray-400 italic">No specific description available.</span>`;
                }
                descriptionDiv.style.opacity = '1';
            }, 100);

            // Update container style
            descriptionContainer.className = "bg-green-50 color-white rounded-2xl p-6 border border-green-200 shadow-md transition-all duration-300 transform";
        });

        if(window.Echo) {
            // Queue Reload Logic
            const queueId = "{{$queue->id}}";
            window.Echo.channel(`live-queue.${queueId}`)
            .listen('QueueSettingsChanged', () => {
                console.log("Queue settings changed, reloading...");
                setTimeout(() => {
                    location.reload()
                }, 1000); 
            });
        }
    });
</script>