<div>
    {{-- Validation Errors --}}
    @if ($errors->any())
    <div class="container fixed bottom-10 left-5 z-50 space-y-4 max-w-md" 
         x-data="{ show: true }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         x-init="setTimeout(() => show = false, 8000)">
        
        <div class="alert flex flex-col gap-2 p-4 mb-4 text-sm font-semibold text-red-800 bg-red-100 border border-red-300 rounded-lg shadow-lg relative">
            <button @click="show = false" class="absolute top-2 right-2 text-red-600 hover:text-red-900 focus:outline-none">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1.93-9.82l-.59.58a1 1 0 01-1.42-1.42l1.3-1.3a1 1 0 011.42 0l3.5 3.5a1 1 0 01-1.42 1.42l-2.8-2.8z" clip-rule="evenodd"></path>
                </svg>
                <span>Errors occurred:</span>
            </div>
            <ul class="list-disc list-inside bg-white/50 p-2 rounded-md mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
