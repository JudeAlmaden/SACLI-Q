<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-4 sm:ml-64 min-h-screen bg-white">
            <!-- Main Container -->
            <div class="mt-8 max-w-7xl mx-auto">
                <!-- Queue Info Card -->
                <div class="bg-white border border-gray-300 rounded-xl shadow-lg overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-8 border-b border-gray-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-700">{{ $queue->name }}</h1>
                                <div class="mt-2 inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                                    {{ $queue->status === 'open' ? 'bg-green-100 text-darj-800' : 'bg-red-100 text-red-800' }}">
                                    <div class="w-2 h-2 rounded-full mr-2 
                                        {{ $queue->status === 'open' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                    {{ ucfirst($queue->status) }}
                                </div>                              
                            </div>

                            <!-- Quick Actions -->
                            <div class="flex space-x-3">
                                <button class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 
                                    text-white rounded-lg transition-colors duration-150 close-queue" data-id="{{ $queue->id }}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $queue->status === 'open' ? "Close":"Open" }} Queue
                                </button>
                                <button class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 
                                    text-white rounded-lg transition-colors duration-150 clear-queue" data-id="{{ $queue->id }}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Clear Queue
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Links -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <div class="relative flex flex-col h-full p-4 border-b border-green-300 bg-white rounded-lg shadow">
                        <div class="flex items-center gap-2 mb-2">
                            <h1 class="text-green-700">URL for live view:</h1>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-mono text-green-800 bg-green-100 px-3 py-2 rounded-md border border-green-300 flex-1 break-all">
                                {{ route('liveQueue', ['code' => $queue->code]) }}
                            </span>
                            <button 
                                data-copy="{{ route('liveQueue', ['code' => $queue->code]) }}"
                                class="copyButton flex items-center px-2 py-2 bg-green-500 text-white hover:bg-green-600 rounded-md border border-green-600 transition-colors"
                                aria-label="Copy to clipboard"
                            >
                                <!-- Copy Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8 2a2 2 0 00-2 2v1H5a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2v-1h1a2 2 0 002-2V7a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H8zM7 4a1 1 0 011-1h4a1 1 0 011 1v1H7V4zm8 3v2H5V7h10zM5 12v3h6v-3H5z" />
                                </svg>
                            </button>
                        </div>
                        <div 
                            class="statusMessage absolute top-16 left-4 text-xs text-green-600 bg-green-50 px-3 py-1 rounded-lg border border-green-300 opacity-0 transition-opacity duration-200"
                        >
                            Copied!
                        </div>
                    </div>

                    <div class="relative flex flex-col h-full p-4 border-b border-green-300 bg-white rounded-lg shadow">
                        <div class="flex items-center gap-2 mb-2">
                            <h1 class="text-green-700">URL for ticketing:</h1>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-mono text-green-800 bg-green-100 px-3 py-2 rounded-md border border-green-300 flex-1 break-all">
                                {{ route('ticketing', ['code' => $queue->code]) }}
                            </span>
                            <button 
                                data-copy="{{ route('ticketing', ['code' => $queue->code]) }}"
                                class="copyButton flex items-center px-2 py-2 bg-green-500 text-white hover:bg-green-600 rounded-md border border-green-600 transition-colors"
                                aria-label="Copy to clipboard"
                            >   
                                <!-- Copy Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8 2a2 2 0 00-2 2v1H5a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2v-1h1a2 2 0 002-2V7a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H8zM7 4a1 1 0 011-1h4a1 1 0 011 1v1H7V4zm8 3v2H5V7h10zM5 12v3h6v-3H5z" />
                                </svg>
                            </button>
                        </div>
                        <div 
                            class="statusMessage absolute top-16 left-4 text-xs text-green-600 bg-green-50 px-3 py-1 rounded-lg border border-green-300 opacity-0 transition-opacity duration-200"
                        >
                            Copied!
                        </div>
                    </div>
                </div>
                    
                    
                    <!-- Window  Section -->
                    <div class="px-6 py-6">

                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-700">Window Availability  and ticket limits</h2>
                        </div>
                    
                        @if ($queue->windows->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-gray-300">
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                                Window
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                                Toggle
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                                                # Tickets generated today
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                                                Daily ticket limit
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-300">
                                        @foreach ($queue->windows as $window)
                                            <tr class="hover:bg-green-50 transition-colors duration-150">
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $window->name }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $window->status == 'open' ? 'bg-green-100 text-gray-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ ucfirst($window->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" 
                                                            class="sr-only peer toggle-window" 
                                                            data-id="{{ $window->id }}" 
                                                            data-queue-id="{{ $queue->id }}"
                                                            {{ $window->status == 'open' ? 'checked' : '' }}>
                                                        <div class="w-11 h-6 bg-gray-300 rounded-full peer 
                                                            peer-focus:ring-4 peer-focus:ring-green-300 
                                                            peer-checked:after:translate-x-full peer-checked:after:border-white 
                                                            after:content-[''] after:absolute after:top-0.5 after:left-[2px] 
                                                            after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all 
                                                            peer-checked:bg-green-600">
                                                        </div>
                                                        <span class="ml-3 text-sm font-medium text-gray-900">
                                                            {{ $window->status == 'open' ? 'Open' : 'Closed' }}
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="text-sm font-medium text-green-900 text-center">
                                                        {{ isset($ticketsPerWindow[$window->id]) ? $ticketsPerWindow[$window->id] : 0 }} 
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 flex justify-center items-center">
                                                    <input 
                                                        type="number" 
                                                        name="limit" 
                                                        value="{{$window->limit}}" 
                                                        class="inputLimit w-20 text-center border-2 border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                        data-window="{{$window->id}}"
                                                    >
                                                </td>                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12 bg-green-50 rounded-lg border-2 border-dashed border-green-300">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No Window</h3>
                                <p class="mt-1 text-sm text-gray-600">No window has been created for this queue yet.</p>
                            </div>
                        @endif
                    </div>
                    
                </div>
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


<script>
    $(document).ready(function () {
        $('.copyButton').on('click', function () {
            const textToCopy = $(this).data('copy');
            const $statusMessage = $(this).closest('.relative').find('.statusMessage');

            if ($statusMessage.length) {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(textToCopy).then(() => {
                        $statusMessage.removeClass('opacity-0').show();

                        setTimeout(() => {
                            $statusMessage.fadeOut(1000, function () {
                                $(this).addClass('opacity-0').hide();
                            });
                        }, 2000);
                    }).catch(err => {
                        $statusMessage.text('Failed to copy text.').css('color', 'red').show();
                        console.error('Copy failed:', err);
                    });
                } else {
                    // Fallback for browsers that do not support navigator.clipboard
                    const tempInput = document.createElement('input');
                    tempInput.value = textToCopy;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    try {
                        document.execCommand('copy');
                        $statusMessage.removeClass('opacity-0').show();
                        setTimeout(() => {
                            $statusMessage.fadeOut(1000, function () {
                                $(this).addClass('opacity-0').hide();
                            });
                        }, 2000);
                    } catch (err) {
                        $statusMessage.text('Failed to copy text.').css('color', 'red').show();
                        console.error('Copy failed:', err);
                    }
                    document.body.removeChild(tempInput);
                }
            }
        });
    });
</script>