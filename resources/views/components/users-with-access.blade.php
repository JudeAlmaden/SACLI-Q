@props([
    'queue',
    'uniqueUsers' => collect(), 
    'accessList' => collect(),
    'userWindows' => collect()
])

<!-- CSRF Meta for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    input[type="checkbox"]{
        width: 20px;
        height: 20px;
    }
</style>
<div class="bg-white rounded-xl shadow-md mb-12">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Users with Access</h2>
        <p class="text-sm text-gray-500 mt-1">Manage user permissions for windows and queues.</p>
    </div>
    <div class="p-6">
        @if ($uniqueUsers->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-sm font-medium text-gray-700">User</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-700" title="Allow user to close their assigned window">Close Assigned Window</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-700" title="Allow user to close ANY window in this queue (Admin)">Close Any Window (Admin)</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-700" title="Allow user to close the entire queue">Close Entire Queue</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-700" title="Allow user to delete all tickets in the queue">Clear Queue</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-700" title="Allow user to change the daily ticket limit for windows">Change Daily Ticket Limit</th>
                            <th class="px-6 py-3 text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($uniqueUsers as $user)
                            @php $access = $accessList->firstWhere('user_id', $user->id); @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 text-center py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $user->name }}
                                    <div class="text-xs text-gray-500 mt-1">
                                        @foreach ($userWindows->get($user->id, collect()) as $windowAccess)
                                            <div>{{ $windowAccess->window->name }}</div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <label>
                                        <input type="checkbox" name="can_close_own_window"
                                               {{ $access->can_close_own_window ? 'checked' : '' }}
                                               class="form-checkbox"
                                               data-id="{{ $user->id }}"
                                               data-field="can_close_own_window">
                                    </label>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <label>
                                        <input type="checkbox" name="can_close_any_window"
                                               {{ $access->can_close_any_window ? 'checked' : '' }}
                                               class="form-checkbox"
                                               data-id="{{ $user->id }}"
                                               data-field="can_close_any_window">
                                    </label>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <label>
                                        <input type="checkbox" name="can_close_queue"
                                               {{ $access->can_close_queue ? 'checked' : '' }}
                                               class="form-checkbox"
                                               data-id="{{ $user->id }}"
                                               data-field="can_close_queue">
                                    </label>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <label>
                                        <input type="checkbox" name="can_clear_queue"
                                               {{ $access->can_clear_queue ? 'checked' : '' }}
                                               class="form-checkbox"
                                               data-id="{{ $user->id }}"
                                               data-field="can_clear_queue">
                                    </label>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <label>
                                        <input type="checkbox" name="can_change_ticket_limit"
                                               {{ $access->can_change_ticket_limit ? 'checked' : '' }}
                                               class="form-checkbox"
                                               data-id="{{ $user->id }}"
                                               data-field="can_change_ticket_limit">
                                    </label>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button
                                        class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800 transition update-access"
                                        data-user-id="{{ $user->id }}"
                                        data-queue-id="{{ $queue->id }}">
                                        Update
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500 text-lg">No users associated on any window.</p>
                <p class="text-gray-400 text-sm mt-1">Go to the <strong>Window</strong> tab and add users to windows to see them here.</p>
            </div>
        @endif
    </div>
</div>

<script>
    $(function () {
        $('.update-access').on('click', function () {
            const row = $(this).closest('tr');
            const userId = $(this).data('user-id');
            const queueId = $(this).data('queue-id');

            const data = {
                can_close_own_window: row.find('[data-field="can_close_own_window"]').prop('checked'),
                can_close_any_window: row.find('[data-field="can_close_any_window"]').prop('checked'),
                can_close_queue: row.find('[data-field="can_close_queue"]').prop('checked'),
                can_clear_queue: row.find('[data-field="can_clear_queue"]').prop('checked'),
                can_change_ticket_limit: row.find('[data-field="can_change_ticket_limit"]').prop('checked'),
            };

            $.ajax({
                url: "{{ route('update-access', ['user_id' => '__userId__', 'queue_id' => '__queueId__']) }}"
                    .replace('__userId__', userId).replace('__queueId__', queueId),
                method: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                data: JSON.stringify(data),
                success: function (response) {
                    alert(response.success ? 'Access privileges updated successfully.' : 'Update failed.');
                },
                error: function () {
                    alert('Error updating access privileges.');
                }
            });
        });
    });
</script>
