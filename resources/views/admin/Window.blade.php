<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-6 sm:ml-64 bg-gray-50 min-h-screen mb-32">
            <div class="max-w-6xl mx-auto space-y-6">
                <!-- Back Button & Header -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <a href="{{ route('admin.queue.view', ['id' => $window->queue_id]) }}"
                        class="inline-flex items-center text-gray-600 hover:text-green-700 transition mb-4">
                        <span class="material-symbols-outlined mr-2">arrow_back</span>
                        Back to Queue
                    </a>
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-green-100 rounded-xl">
                                <span class="material-symbols-outlined text-green-700 text-3xl">desktop_windows</span>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $window->name }}</h1>
                                <p class="text-gray-500 text-sm">{{ $window->description }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                            Active Window
                        </span>
                    </div>
                </div>

                <!-- Analytics Component -->
                <x-window-analytics :analytics="$analytics" :allUsers="$allUsers"/>

                <!-- Assign Users Card -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-xl p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Assign Users</h2>
                    @if ($allUsers->diff($users)->isNotEmpty())
                        <form action="{{ route('admin.window.user.add', ['id' => $window->id]) }}" method="POST" class="flex gap-3">
                            @csrf
                            <select name="user_id" class="flex-1 p-2 border border-gray-200 rounded-lg">
                                @foreach ($allUsers->diff($users) as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->account_id }})</option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                Assign
                            </button>
                        </form>
                    @else
                        <p class="text-gray-500">All users have been assigned.</p>
                    @endif
                </div>

                <!-- Users with Access Card -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-xl p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Users with Access ({{ $users->count() }})</h2>
                    @if ($users->isNotEmpty())
                        <table class="w-full">
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3">
                                            <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $user->account_id }}</p>
                                        </td>
                                        <td class="py-3 text-right">
                                            <form action="{{ route('admin.window.user.remove', ['id' => $window->id, 'user_id' => $user->id]) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Remove {{ $user->name }}?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-gray-500">No users assigned yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>
</x-Dashboard>