<x-Dashboard>
    <x-slot name="content">
        <div class="mt- p-6 sm:ml-64 min-h-screen bg-gray-50">
            <!-- Main Container -->
            <div class="max-w-7xl mx-auto pt-8">
                
                <!-- Queue Header Card -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-8 overflow-hidden mt-8">
                    <div class="p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-3xl font-bold text-gray-900">{{ $queue->name }}</h1>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $queue->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <span class="w-2 h-2 rounded-full mr-2 {{ $queue->status === 'open' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    {{ ucfirst($queue->status) }}
                                </span>
                            </div>
                            <p class="text-gray-500">Manage queue status, windows, and ticket daily limits.</p>
                        </div>

                        <!-- Quick Actions -->
                        <div class="flex flex-wrap gap-3">
                            <button 
                                class="inline-flex items-center px-4 py-2.5 bg-green-600 text-white font-medium rounded-lg transition-colors shadow-sm close-queue {{ !$canCloseQueue ? 'opacity-50 cursor-not-allowed' : 'hover:bg-green-700' }}" 
                                data-id="{{ $queue->id }}"
                                {{ !$canCloseQueue ? 'disabled' : '' }}>
                                <span class="material-symbols-outlined mr-2 text-[20px]">
                                    {{ $queue->status === 'open' ? "lock" : "lock_open" }}
                                </span>
                                {{ $queue->status === 'open' ? "Stop Issuing Tickets" : "Start Issuing Tickets" }}
                            </button>
                            
                            <button 
                                class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg transition-colors shadow-sm clear-queue {{ !$canClearQueue ? 'opacity-50 cursor-not-allowed' : 'hover:bg-red-700' }}" 
                                data-id="{{ $queue->id }}"
                                {{ !$canClearQueue ? 'disabled' : '' }}>
                                <span class="material-symbols-outlined mr-2 text-[20px]">delete_sweep</span>
                                Delete All Tickets
                            </button>
                        </div>
                    </div>
                    
                     <!-- Queue Links (Collapsible/Integrated) -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                        <x-copy-links :queue="$queue"/>
                    </div>
                </div>


                <!-- Windows Management Section -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Window Availability & Limits</h2>
                        <p class="text-sm text-gray-500 mt-1">Control which windows are open and set their daily ticket limits.</p>
                    </div>
                </div>
            
                @if ($queue->windows->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($queue->windows as $window)
                            @php
                                $canToggleThisWindow = $canCloseAnyWindow || 
                                    $userAccess->where('window_group_id', $window->id)
                                               ->where('can_close_own_window', true)
                                               ->isNotEmpty();
                            @endphp
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
                                <div class="p-5">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">{{ $window->name }}</h3>
                                             <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $window->status == 'open' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                                {{ ucfirst($window->status) }}
                                            </span>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer {{ !$canToggleThisWindow ? 'opacity-50 cursor-not-allowed' : '' }}">
                                            <input type="checkbox" 
                                                class="sr-only peer toggle-window" 
                                                data-id="{{ $window->id }}" 
                                                data-queue-id="{{ $queue->id }}"
                                                {{ $window->status == 'open' ? 'checked' : '' }}
                                                {{ !$canToggleThisWindow ? 'disabled' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                        </label>
                                    </div>

                                    <div class="space-y-4">
                                         <div class="bg-gray-50 p-3 rounded-lg flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Generated Today</span>
                                            <span class="font-bold text-gray-900">{{ isset($ticketsPerWindow[$window->id]) ? $ticketsPerWindow[$window->id] : 0 }}</span>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Daily Ticket Limit</label>
                                            <div class="relative">
                                                 <input 
                                                    type="number" 
                                                    name="limit" 
                                                    value="{{$window->limit}}" 
                                                    class="inputLimit w-full border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm {{ !$canChangeLimit ? 'bg-gray-100 cursor-not-allowed text-gray-500' : '' }}"
                                                    data-window="{{$window->id}}"
                                                    {{ !$canChangeLimit ? 'disabled readonly' : '' }}
                                                >
                                                @if($canChangeLimit)
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                    <span class="material-symbols-outlined text-gray-400 text-sm">edit</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                        <span class="material-symbols-outlined text-gray-300 text-6xl mb-4">desktop_windows</span>
                        <h3 class="text-lg font-medium text-gray-900">No Windows Found</h3>
                        <p class="text-gray-500 mt-1">No windows have been created for this queue yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>
</x-Dashboard>

<script>
    const routes = {
        toggleWindow: "{{ route('window.toggle', ['id' => ':id']) }}",
        toggleQueue: "{{ route('queue.toggle', ['id' => ':id']) }}",
        clearQueue: "{{ route('queue.clear', ['id' => ':id']) }}",
        setLimit:"{{ route('window.setLimit',['window_id' => ':window_id', 'limit'=>':limit']) }}",
    };
    

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-window').forEach(toggle => {
            toggle.addEventListener('change', function () {
                const id = this.getAttribute('data-id');
                const queueId = this.getAttribute('data-queue-id');

                fetch(routes.toggleWindow.replace(':id', id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        queue_id: queueId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                        } else {
                            alert(data.message);
                        }
                        location.reload()
                    })
                    .catch(error => {
                        alert(data.message);
                    });
            });
        });

        document.querySelector('.close-queue').addEventListener('click', function () {
            const id = this.getAttribute('data-id');

            fetch(routes.toggleQueue.replace(':id', id), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Queue status updated successfully.');
                    } else {
                        alert(data.message);
                    }

                    setTimeout(() => location.reload(true), 100);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the queue status.');
                });
        });

        document.querySelector('.clear-queue').addEventListener('click', function () {
            const id = this.getAttribute('data-id');

            if (!confirm('Are you sure you want to DELETE ALL TICKETS? This action cannot be undone.')) {
                return;
            }

            fetch(routes.clearQueue.replace(':id', id), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Queue cleared successfully.');
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while clearing the queue.');
                });
        });

        $('.copyButton').on('click', function () {
            const textToCopy = $(this).data('copy');
            const $statusMessage = $(this).nextAll('.statusMessage').first();

            if ($statusMessage.length) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    $statusMessage.removeClass('opacity-0').css('display', 'block');

                    setTimeout(() => {
                        $statusMessage.fadeOut(1000, function () {
                            $(this).addClass('opacity-0').css('display', 'none');
                        });
                    }, 4000);

                }).catch(err => {
                    $statusMessage.text('Failed to copy text.').css('color', 'red');
                    console.error('Copy failed:', err);
                });
            } else {
                console.error('No sibling element with the class "statusMessage" found.');
            }
        });
    
        //Changing limit on how many tickets can be generated each day
        $('.inputLimit').on('keypress', function (event) {
            if (event.which === 13) { // "Enter" key
                const limit = $(this).val(); // Get the input value
                const window_id = $(this).data('window'); // Get the data-window attribute

                fetch(routes.setLimit.replace(':window_id', window_id).replace(':limit', limit), {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {

                    if (data.success) {
                        alert('Daily ticket limit has been changed.');
                    } else {
                        alert(data.message);
                    }

                    setTimeout(() => location.reload(true), 100);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the data');
                });
                

            }
        });
    });

</script>
