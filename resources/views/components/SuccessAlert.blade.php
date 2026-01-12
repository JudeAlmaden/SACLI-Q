<div>
    @if (session()->has('success'))
    <div class="container fixed bottom-10 left-5 z-50 space-y-4 max-w-md">
        @foreach ((array) session('success') as $successMessage)
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 x-init="setTimeout(() => show = false, 5000)"
                 class="alert flex items-center gap-4 p-4 mb-4 text-sm font-semibold text-green-800 bg-green-100 border border-green-300 rounded-lg shadow-lg relative group">
                
                <button @click="show = false" class="absolute top-2 right-2 text-green-600 hover:text-green-900 focus:outline-none opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <svg class="w-5 h-5 text-green-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1.93-9.82l-.59.58a1 1 0 01-1.42-1.42l1.3-1.3a1 1 0 011.42 0l3.5 3.5a1 1 0 01-1.42 1.42l-2.8-2.8z" clip-rule="evenodd"></path>
                </svg>
                {{ $successMessage }}
            </div>
        @endforeach
    </div>
    @endif
</div>
