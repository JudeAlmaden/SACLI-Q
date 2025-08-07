<x-Dashboard>
    <x-slot name="content">
        <div class="p-6 sm:ml-64 bg-white min-h-screen flex flex-col items-center pt-20">
            <div class="w-full max-w-6xl space-y-8 mt">
                <!-- Window Details -->
                <div class="shadow-lg p-6 border rounded-lg">
                    <a href="{{route('admin.queue.view', ['id' => $window->queue_id])}}"
                        class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="flex items-center space-x-4 mb-4">
                        <i class="fas fa-window-maximize text-3xl text-green-600"></i>
                        <div>
                            <h1 class="text-3xl font-bold">{{ $window->name }}</h1>
                            <span class="text-gray-500">Window Details</span>
                        </div>
                    </div>
                    <h2 class="text-lg font-bold">Description</h2>
                    <p class="text-sm text-gray-700">{{ $window->description }}</p>
                </div>

                <x-window-analytics :analytics="$analytics" :allUsers="$allUsers"/>
                <!-- Assign Users -->
                <div class="shadow-lg p-6 border rounded-lg">
                    <h2 class="text-xl font-bold mb-4">Assign Users</h2>
                    @if ($allUsers->diff($users)->isNotEmpty())
                        <form action="{{ route('admin.window.user.add', ['id' => $window->id]) }}" method="POST"
                            class="space-y-4">
                            @csrf
                            <select id="user_id" name="user_id" class="w-full p-2 border rounded">
                                @foreach ($allUsers as $user)
                                    @if (!$users->contains($user))
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->account_id }})</option>
                                    @endif
                                @endforeach
                            </select>
                            <button type="submit"
                                class="w-full py-2 bg-green-600 text-white rounded hover:bg-green-700">Assign</button>
                        </form>
                    @else
                        <p class="text-gray-700">All possible users have already been assigned</p>
                    @endif
                </div>

                <!-- Users with Access -->
                <div class="shadow-lg p-6 border rounded-lg" style="margin-bottom:20%">
                    <h2 class="text-xl font-bold mb-4">Users with Access</h2>
                    @if ($users->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full border">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="p-3 text-left text-sm font-medium text-gray-600">User</th>
                                        <th class="p-3 text-left text-sm font-medium text-gray-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr class="border-t">
                                            <td class="p-3 text-sm">{{ $user->name }} ({{$user->account_id}})</td>
                                            <td class="p-3 text-sm">
                                                <form
                                                    action="{{ route('admin.window.user.remove', ['id' => $window->id, 'user_id' => $user->id]) }}"
                                                    method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash-alt"></i> Remove
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-700">No users have access.</p>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>
</x-Dashboard>