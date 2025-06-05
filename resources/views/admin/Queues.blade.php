<x-Dashboard>
    <x-slot name="content">         
        <div class="mt-8 p-4 sm:ml-64 bg-white min-h-screen">
            <div class="mt-8 p-4 border-2 border-gray-200 border-dashed rounded-lg">
            
                <div class="flex items-center space-x-4">
                    <i class="fas fa-list text-3xl text-green-600"></i>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Queue Management</h1>
                        <span class="text-md text-gray-500">Create and manage queues</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <form method="GET" action="{{ route('admin.queue.list') }}" class="flex-auto sm:w-auto sm:mr-4">
                        <div class="flex items-center w-100 sm:w-auto mt-4">
                            <input type="text" name="search" placeholder="Search queues..."  class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            <button type="submit" class="ml-2 px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">Search</button>
                        </div>
                    </form>
                    <button id="toggleCreateQueue" onclick="toggleModal('createQueue')" class="mt-4 sm:mt-0 flex items-center px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">
                        <i class="fas fa-plus mr-2"></i>
                        Create New Queue
                    </button>
                </div>

                <hr>

                <div class="mt-8 relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-8/12">Queue Name</th>
                                <th scope="col" class="px-6 py-3 w-2/12">Action</th>
                                <th scope="col" class="px-6 py-3 w-2/12">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($queues as $queue)
                                <tr class="bg-white border-b">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $queue->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{route('admin.queue.view',['id'=>$queue->id])}}" class="text-gray-600 hover:text-green-500 flex items-center">
                                            <i class="fas fa-eye mr-2"></i> View
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button type="submit" class="text-red-600 hover:text-red-900 flex items-center">
                                                <button onclick="deleteQueueModal({{ $queue->id }})" class="text-red-600 hover:text-red-900 ml-4">Delete</button>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $queues->links() }}
                </div>
            </div>
        </div> 

        {{-- Create Modal --}}
        <x-modal id="createQueue" title="Create new Queue">
            <form id="createQueueForm" class="mt-4" action="{{ route('admin.queue.create') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Queue Name</label>
                    <input required type="text" id="name" name="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    @error('name')
                        <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" onclick="toggleModal('createQueue')" class="w-1/2 px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 mr-2">Cancel</button>
                    <button type="submit" class="w-1/2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">Create Queue</button>
                </div>
            </form>
        </x-modal>

        
        {{-- Delete Modal --}}
        <x-modal id="deleteQueueModal" title="Confirm Delete">
            <form id="deleteQueueForm" method="POST">
                @csrf
                @method('DELETE')
                <p class="text-sm text-gray-500 mb-4">Are you sure you want to delete this queue? This action cannot be undone.</p>
                <div class="flex justify-end">
                    <button type="button" onclick="toggleModal('deleteQueueModal')" class="mr-2 px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md">Delete</button>
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
