@props(['id', 'title'])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 text-center">
        <div class="fixed inset-0 bg-gray-500 opacity-75 " style="z-index: 40;"></div>
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full z-50 p-6 text-start">
            <h2 class="text-xl font-semibold mb-4 text-gray-900">{{ $title }}</h2>
            {{ $slot }}
        </div>
    </div>
</div>
