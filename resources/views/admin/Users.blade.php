<x-Dashboard>
    <x-slot name="content">         
        <div class="mt-8 p-4 sm:ml-64 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto mt-16">
                <!-- Header Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-green-100 rounded-xl">
                                <span class="material-symbols-outlined text-green-700 text-3xl">group</span>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                                <p class="text-gray-500 text-sm">Create and manage employee accounts</p>
                            </div>
                        </div>
                        <button onclick="toggleModal('createUserModal')" class="flex items-center px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all shadow-sm hover:shadow-md">
                            <span class="material-symbols-outlined mr-2 text-xl">person_add</span>
                            Create User
                        </button>
                    </div>
                </div>

                <!-- Search Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
                    <form method="GET" action="{{ route('user.list') }}" class="flex items-center gap-3">
                        <div class="relative flex-1">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                            <input type="text" name="search" placeholder="Search users by name or ID..." 
                                value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                        </div>
                        <button type="submit" class="px-5 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-all">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Users Table Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Account ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-green-50 transition-colors cursor-pointer group"
                                        onclick="openEditUserModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->account_id) }}')">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-semibold text-sm shadow-sm">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div class="ml-3">
                                                    <p class="font-medium text-gray-900 group-hover:text-green-700 transition-colors">{{ $user->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-600 font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $user->account_id }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($user->access_type == 'admin')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <span class="material-symbols-outlined text-sm mr-1">shield</span>
                                                    Admin
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <span class="material-symbols-outlined text-sm mr-1">person</span>
                                                    Employee
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2" onclick="event.stopPropagation()">
                                                <button onclick="openEditUserModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->account_id) }}')" 
                                                    class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all" title="Edit">
                                                    <span class="material-symbols-outlined text-xl">edit</span>
                                                </button>
                                                @if ($user->access_type == 'admin')
                                                    <span class="p-2 text-gray-300 cursor-not-allowed" title="Cannot delete admin">
                                                        <span class="material-symbols-outlined text-xl">delete</span>
                                                    </span>
                                                @else
                                                    <button onclick="openDeleteUserModal({{ $user->id }})" 
                                                        class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete">
                                                        <span class="material-symbols-outlined text-xl">delete</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Modal --}}
        <x-modal id="editUserModal" title="Edit User">
            <form id="editUserForm" method="POST" action="{{ route('user.edit') }}">
                @csrf
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee Name</label>
                    <input readonly type="text" id="edit_name" name="name" class="w-full p-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-600">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Account ID</label>
                    <input readonly type="text" id="edit_account_id" name="account_id" class="w-full p-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-600">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" class="w-full p-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Enter new password">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full p-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Confirm new password">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="toggleModal('editUserModal')" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">Update User</button>
                </div>
            </form>
        </x-modal>

        {{-- Create Modal --}}
        <x-modal id="createUserModal" title="Create New User">
            <form method="POST" action="{{ route('user.save') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee Name</label>
                    <input required type="text" name="name" class="w-full p-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Enter employee name">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Account ID</label>
                    <input required type="text" name="account_id" class="w-full p-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Enter account ID">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input required type="password" name="password" class="w-full p-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Enter password">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input required type="password" name="password_confirmation" class="w-full p-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Confirm password">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="toggleModal('createUserModal')" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">Create User</button>
                </div>
            </form>
        </x-modal>

        {{-- Delete Modal --}}
        <x-modal id="deleteUserModal" title="Confirm Delete">
            <form id="deleteUserForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="text-center py-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-red-600 text-3xl">warning</span>
                    </div>
                    <p class="text-gray-600 mb-2">Are you sure you want to delete this user?</p>
                    <p class="text-sm text-gray-400">This action cannot be undone.</p>
                </div>
                <div class="flex justify-center gap-3 mt-6">
                    <button type="button" onclick="toggleModal('deleteUserModal')" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">Cancel</button>
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
