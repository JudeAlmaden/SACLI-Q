<x-Dashboard>
    <x-slot name="content">         
        <div class="mt-8 p-4 sm:ml-64 bg-white min-h-screen">
            <div class="mt-8 p-4 border-2 border-gray-200 border-dashed rounded-lg">
                <!-- Header -->
                <div class="flex items-center space-x-4">
                    <i class="fas fa-users text-3xl text-green-700"></i>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
                        <span class="text-md text-gray-500">Create and manage employee accounts</span>
                    </div>
                </div>

                <!-- Search and Create -->
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <form method="GET" action="{{ route('user.list') }}" class="flex-auto sm:w-auto sm:mr-4">
                        <div class="flex items-center w-100 sm:w-auto mt-4">
                            <input type="text" name="search" placeholder="Search users..." class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            <button type="submit" class="ml-2 px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">Search</button>
                        </div>
                    </form>
                    <button onclick="toggleModal('createUserModal')" class="mt-4 sm:mt-0 flex items-center px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">
                        <i class="fas fa-user-plus mr-2"></i> Create New User
                    </button>
                </div>

                <!-- User Table -->
                <div class="mt-5 relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">User Name</th>
                                <th class="px-6 py-3">Account ID</th>
                                <th class="px-6 py-3">Access Type</th>
                                <th class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="bg-white border-b">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->account_id }}</td>
                                    <td class="px-6 py-4">{{ ucfirst($user->access_type) }}</td>
                                    <td class="px-6 py-4">
                                        <button onclick="openEditUserModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->account_id) }}')" class="text-green-700 hover:text-green-900">Edit</button>
                                        @if ($user->access_type == 'admin')
                                            <span class="text-gray-500 cursor-not-allowed ml-4">Delete</span>
                                        @else
                                            <button onclick="openDeleteUserModal({{ $user->id }})" class="text-red-600 hover:text-red-900 ml-4">Delete</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $users->links() }}</div>
            </div>
        </div>

        {{-- Edit Modal --}}
        <x-modal id="editUserModal" title="Edit User">
            <form id="editUserForm" method="POST" action="{{ route('user.edit') }}">
                @csrf
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Employee Name</label>
                    <input readonly type="text" id="edit_name" name="name" class="w-full p-2 border rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Account ID</label>
                    <input readonly type="text" id="edit_account_id" name="account_id" class="w-full p-2 border rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" name="password" class="w-full p-2 border rounded-md" placeholder="New Password">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full p-2 border rounded-md" placeholder="Confirm Password">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="toggleModal('editUserModal')" class="mr-2 px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-md">Update</button>
                </div>
            </form>
        </x-modal>

        {{-- Create Modal --}}
        <x-modal id="createUserModal" title="Create New User">
            <form method="POST" action="{{ route('user.save') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Employee Name</label>
                    <input required type="text" name="name" class="w-full p-2 border rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Account ID</label>
                    <input required type="text" name="account_id" class="w-full p-2 border rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input required type="password" name="password" class="w-full p-2 border rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input required type="password" name="password_confirmation" class="w-full p-2 border rounded-md">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="toggleModal('createUserModal')" class="mr-2 px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-md">Create</button>
                </div>
            </form>
        </x-modal>

        {{-- Delete Modal --}}
        <x-modal id="deleteUserModal" title="Confirm Delete">
            <form id="deleteUserForm" method="POST">
                @csrf
                @method('DELETE')
                <p class="text-sm text-gray-500 mb-4">Are you sure you want to delete this user? This action cannot be undone.</p>
                <div class="flex justify-end">
                    <button type="button" onclick="toggleModal('deleteUserModal')" class="mr-2 px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</button>
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

    function openEditUserModal(id, name, account_id) {
        document.getElementById('edit_user_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_account_id').value = account_id;
        toggleModal('editUserModal');
    }

    function openDeleteUserModal(userId) {
        const form = document.getElementById('deleteUserForm');
        form.action = `{{ route('user.delete', ['id' => ':userId']) }}`.replace(':userId', userId);
        toggleModal('deleteUserModal');
    }
</script>
