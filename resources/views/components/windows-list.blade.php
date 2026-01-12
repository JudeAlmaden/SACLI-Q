@props(['queue'])

<div class="mb-12">
    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Window Groups</h2>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @if ($queue->windows && $queue->windows->isNotEmpty())
            @foreach ($queue->windows as $window)
                <div
                    class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-transform duration-200 hover:scale-105">
                    <a href="{{ route('admin.window.view', ['id' => $window->id]) }}" class="block p-6">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-window-restore text-green-700"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $window->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $window->description }}</p>
                            </div>
                        </div>
                    </a>
                    <form action="{{ route('admin.window.delete', ['id' => $window->id]) }}" method="POST"
                        class="border-t border-gray-200"
                        onsubmit="return confirm('Are you sure you want to delete this window group? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full py-3 text-sm font-semibold text-red-600 hover:text-red-800 transition">Remove</button>
                    </form>
                </div>
            @endforeach
        @endif
        <!-- Add New Window Button Styled as a Grid Item -->
        <div
            class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-transform duration-200 hover:scale-105 flex justify-center items-center">
            <button onclick="toggleModal('modal')"
                class="flex flex-col items-center justify-center p-6 text-gray-600 hover:text-green-700 transition">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-plus text-green-700 text-2xl"></i>
                </div>
                <span class="mt-3 text-lg font-medium">Add New</span>
            </button>
        </div>
    </div>
</div>

<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }
</script>