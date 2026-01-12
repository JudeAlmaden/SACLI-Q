<x-Dashboard>
    <x-slot name="content">         
        <div class="mt-8 p-4 sm:ml-64 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto">
                <!-- Header Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-green-100 rounded-xl">
                                <span class="material-symbols-outlined text-green-700 text-3xl">queue</span>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Queue Management</h1>
                                <p class="text-gray-500 text-sm">Create and manage service queues</p>
                            </div>
                        </div>
                        <button onclick="toggleModal('createQueue')" class="flex items-center px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all shadow-sm hover:shadow-md">
                            <span class="material-symbols-outlined mr-2 text-xl">add</span>
                            Create Queue
                        </button>
                    </div>
                </div>

                <!-- Search Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
                    <form method="GET" action="{{ route('admin.queue.list') }}" class="flex items-center gap-3">
                        <div class="relative flex-1">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                            <input type="text" name="search" placeholder="Search queues..." 
                                value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                        </div>
                        <button type="submit" class="px-5 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-all">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Queues Table Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Queue Name</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($queues as $queue)
                                    <tr class="hover:bg-green-50 transition-colors cursor-pointer group"
                                        onclick="window.location.href='{{ route('admin.queue.view', ['id' => $queue->id]) }}'">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white shadow-sm">
                                                    <span class="material-symbols-outlined">lists</span>
                                                </div>
                                                <div class="ml-4">
                                                    <p class="font-medium text-gray-900 group-hover:text-green-700 transition-colors">{{ $queue->name }}</p>
                                                    <p class="text-xs text-gray-400">Click to view details</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2" onclick="event.stopPropagation()">
                                                <a href="{{ route('admin.queue.view', ['id' => $queue->id]) }}" 
                                                    class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all" title="View">
                                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                                </a>
                                                <button onclick="deleteQueueModal({{ $queue->id }})" 
                                                    class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete">
                                                    <span class="material-symbols-outlined text-xl">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($queues->isEmpty())
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-outlined text-gray-400 text-3xl">inbox</span>
                            </div>
                            <p class="text-gray-500">No queues found</p>
                            <p class="text-sm text-gray-400">Create a new queue to get started</p>
                        </div>
                    @endif
                    
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $queues->links() }}
                    </div>
                </div>
            </div>
        </div> 

        {{-- Create Modal --}}
        <x-modal id="createQueue" title="Create New Queue">
            <form id="createQueueForm" action="{{ route('admin.queue.create') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Queue Name</label>
                    <input required type="text" id="name" name="name" 
                        class="w-full p-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all" 
                        placeholder="Enter queue name">
                    @error('name')
                        <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="toggleModal('createQueue')" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">Create Queue</button>
                </div>
            </form>
        </x-modal>

        
        {{-- Delete Modal --}}
        <x-modal id="deleteQueueModal" title="Confirm Delete">
            <form id="deleteQueueForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="text-center py-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-red-600 text-3xl">warning</span>
                    </div>
                    <p class="text-gray-600 mb-2">Are you sure you want to delete this queue?</p>
                    <p class="text-sm text-gray-400">This action cannot be undone.</p>
                </div>
                <div class="flex justify-center gap-3 mt-6">
                    <button type="button" onclick="toggleModal('deleteQueueModal')" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all">Delete</button>
                </div>
            </form>
        </x-modal>

    </x-slot>
</x-Dashboard>

<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function deleteQueueModal(queueId) {
        const form = document.getElementById('deleteQueueForm');
        form.action = `{{ route('admin.queue.delete', ['id' => ':queueId']) }}`.replace(':queueId', queueId);
        
        toggleModal('deleteQueueModal');
    }
</script>
