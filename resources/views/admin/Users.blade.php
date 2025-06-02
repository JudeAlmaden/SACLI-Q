<x-Dashboard>
    <x-slot name="content">         
        <div class="mt-8 p-4 sm:ml-64 bg-white min-h-screen">
            <div class="mt-8 p-4 border-2 border-gray-200 border-dashed rounded-lg">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-users text-3xl text-green-700"></i>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
                        <span class="text-md text-gray-500">Create and manage employee accounts</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <form method="GET" action="{{ route('user.list') }}" class="flex-auto sm:w-auto sm:mr-4">
                        <div class="flex items-center w-100 sm:w-auto mt-4">
                            <input type="text" name="search" placeholder="Search users..." class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            <button type="submit" class="ml-2 px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">Search</button>
                        </div>
                    </form>
                    <button id="toggleModalButton" class="mt-4 sm:mt-0 flex items-center px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">
                        <i class="fas fa-user-plus mr-2"></i>
                        Create New User
                    </button>
                </div>

                <!-- Modal -->
                <div id="createUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h1 class="text-xl font-semibold text-gray-900">Create New User</h1>
                                <form id="createUserForm" class="mt-4" action="{{ route('user.edit') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700">Employee Name</label>
                                        <input required type="text" id="name" name="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                    </div>
                                    <div class="mb-4">
                                        <label for="account_id" class="block text-sm font-medium text-gray-700">Account ID</label>
                                        <input required type="text" id="account_id" name="account_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                    </div>
                                    <div class="mb-4">
                                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                        <input required type="password" id="password" name="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                    </div>
                                    <div class="mb-4">
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                        <input required type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button" id="closeModalButton" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 mr-2">Cancel</button>
                                        <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">Create User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">User Name</th>
                                <th scope="col" class="px-6 py-3">Account ID</th>
                                <th scope="col" class="px-6 py-3">Access Type</th>
                                <th scope="col" class="px-6 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="bg-white border-b">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->account_id }}</td>
                                    <td class="px-6 py-4">{{ ucfirst($user->access_type) }}</td>
                                    <td class="px-6 py-4">
                                        <button type="button"
                                            onclick="openEditUserModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->account_id) }}', '{{ $user->access_type }}')"
                                            class="text-green-700 hover:text-green-900">
                                            Edit
                                        </button>
                                        @if ($user->access_type == 'admin')
                                            <span class="text-gray-500 cursor-not-allowed ml-4">Delete</span>
                                        @else
                                            <form action="{{ route('user.delete', ['id' => $user->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
                <!-- Edit Modal -->
                <div id="editUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h1 class="text-xl font-semibold text-gray-900">Edit User</h1>
                                <form id="editUserForm" method="POST" action="{{ route('user.edit') }}">

                                    @csrf
                                    <input type="hidden" id="edit_user_id" name="user_id">
                                    <div class="mb-4">
                                        <label for="edit_name" class="block text-sm font-medium text-gray-700">Employee Name</label>
                                        <input readonly type="text" id="edit_name" name="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                    </div>
                                    <div class="mb-4">
                                        <label for="edit_account_id" class="block text-sm font-medium text-gray-700">Account ID</label>
                                        <input readonly type="text" id="edit_account_id" name="account_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                    </div>
                                    <div class="mb-4">
                                        <label for="edit_password" class="block text-sm font-medium text-gray-700">New Password</label>
                                        <input type="password" id="edit_password" name="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" placeholder="New password">
                                    </div>
                                    <div class="mb-4">
                                        <label for="edit_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                        <input type="password" id="edit_password_confirmation" name="password_confirmation" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" placeholder="Confirm New Password">
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button" onclick="closeEditUserModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 mr-2">Cancel</button>
                                        <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800">Update User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>         
    </x-slot>
</x-Dashboard>

<script>
    document.getElementById('toggleModalButton').addEventListener('click', function() {
        document.getElementById('createUserModal').classList.remove('hidden');
    });

    document.getElementById('closeModalButton').addEventListener('click', function() {
        document.getElementById('createUserModal').classList.add('hidden');
    });

    document.getElementById('toggleModalButton').addEventListener('click', function () {
        document.getElementById('createUserModal').classList.remove('hidden');
    });

    document.getElementById('closeModalButton').addEventListener('click', function () {
        document.getElementById('createUserModal').classList.add('hidden');
    });

    function openEditUserModal(id, name, account_id, access_type) {
        // Fill form fields
        document.getElementById('edit_user_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_account_id').value = account_id;

        // Show modal
        document.getElementById('editUserModal').classList.remove('hidden');
    }

    function closeEditUserModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }
</script>