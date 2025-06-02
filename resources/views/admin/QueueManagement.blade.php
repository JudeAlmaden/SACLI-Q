<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-12 sm:ml-64 bg-white min-h-screen">
            <div class="p-8 bg-gray-50 border border-gray-200 rounded-xl shadow-lg">
                <div class="mb-12">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $queue->name }}</h1>
                    <p class="text-lg text-gray-500">Manage access and details for this queue.</p>
                </div>

                
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
                    
                <div class="mb-12">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Window Groups</h2>
                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @if ($queue->windows && $queue->windows->isNotEmpty())
                            @foreach ($queue->windows as $window)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-transform duration-200 hover:scale-105">
                                    <a href="{{ route('admin.window.view', ['id' => $window->id]) }}" class="block p-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="p-3 bg-green-100 rounded-full">
                                                <i class="fas fa-window-restore text-green-700"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900">{{ $window->name }}</h3>
                                                <p class="text-sm text-gray-500">{{ $window->description }}</p>
                                            </div>
                                        </div>
                                    </a>
                                    <form action="{{ route('admin.window.delete', ['id' => $window->id]) }}" method="POST" class="border-t border-gray-200">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full py-3 text-sm font-semibold text-red-600 hover:text-red-800 transition">Remove</button>
                                    </form>
                                </div>
                            @endforeach
                    @endif
                        <!-- Add New Window Button Styled as a Grid Item -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-transform duration-200 hover:scale-105 flex justify-center items-center">
                            <button onclick="toggleModal(true)" class="flex flex-col items-center justify-center p-6 text-gray-600 hover:text-green-700 transition">
                                <div class="p-3 bg-green-100 rounded-full">
                                    <i class="fas fa-plus text-green-700 text-2xl"></i>
                                </div>
                                <span class="mt-3 text-lg font-medium">Add New</span>
                            </button>
                        </div>
                    </div>
                </div>
                {{-- Copy Links --}}
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
                                
                <!-- Modal -->
                <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Add New Window Group</h2>
                        <form action="{{ route('admin.window.create', ['id' => $queue->id]) }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" id="name" name="name" class="w-full p-3 border border-gray-300 rounded-md focus:ring focus:ring-green-300 transition" required>
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" name="description" class="w-full p-3 border border-gray-300 rounded-md focus:ring focus:ring-green-300 transition" rows="3" required></textarea>
                            </div>
                            <div class="flex justify-between">
                                <button type="button" onclick="toggleModal(false)" class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-md hover:bg-gray-600 transition">Cancel</button>
                                <button type="submit" class="px-6 py-3 bg-green-700 text-white font-semibold rounded-md hover:bg-green-800 focus:ring focus:ring-green-300 transition">Add Window Group</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md mb-12">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">Users with Access</h2>
                    </div>
                    <div class="p-6">
                        @if ($uniqueUsers->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-6 py-3 text-sm font-medium text-gray-700">User</th>
                                            <th class="px-6 py-3 text-sm font-medium text-gray-700">Close Own Window</th>
                                            <th class="px-6 py-3 text-sm font-medium text-gray-700">Close Any Window</th>
                                            <th class="px-6 py-3 text-sm font-medium text-gray-700">Close Queue</th>
                                            <th class="px-6 py-3 text-sm font-medium text-gray-700">Clear Queue</th>
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
                                                    <input type="checkbox" {{ $access->can_close_own_window ? 'checked' : '' }} class="form-checkbox" data-id="{{ $user->id }}" data-field="can_close_own_window">
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input type="checkbox" {{ $access->can_close_any_window ? 'checked' : '' }} class="form-checkbox" data-id="{{ $user->id }}" data-field="can_close_any_window">
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input type="checkbox" {{ $access->can_close_queue ? 'checked' : '' }} class="form-checkbox" data-id="{{ $user->id }}" data-field="can_close_queue">
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input type="checkbox" {{ $access->can_clear_queue ? 'checked' : '' }} class="form-checkbox" data-id="{{ $user->id }}" data-field="can_clear_queue">
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <button class="px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800 transition update-access" data-user-id="{{ $user->id }}" data-queue-id="{{ $queue->id }}">Update</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-600">No users have access to this queue.</p>
                        @endif
                    </div>
                </div>

                <!-- Upload Section -->
                <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-3xl mx-auto">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-1">Advertisement</h2>
                    <p class="text-sm text-gray-500 mb-4">You can place your ad content here.</p>

                    @csrf

                    <div id="uploadMessage" class="hidden flex items-center justify-center mt-4 text-gray-700">
                        <svg class="animate-spin h-5 w-5 mr-3 text-blue-600" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                        </svg>
                        Uploading files, please wait...
                    </div>

                    <div class="flex justify-center">
                        <label for="image" class="cursor-pointer flex items-center gap-2 text-gray-600 hover:text-gray-800 mb-12">
                            <span class="material-symbols-outlined text-2xl">Files</span>
                            <span>Select Files</span>
                        </label>
                        <input type="file" id="image" name="File[]" accept="image/*,video/*" multiple class="hidden">
                    </div>

                    <div id="preview" class="flex flex-wrap gap-3 justify-center">
                        @php
                            $mediaAds = json_decode($queue->media_advertisement ?? '[]', true) ?? [];
                        @endphp
                        @foreach ($mediaAds as $mediaPath)
                            @php
                                $fullPath = asset('storage/' . $mediaPath);
                                $ext = pathinfo($mediaPath, PATHINFO_EXTENSION);
                            @endphp

                            @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <img src="{{ $fullPath }}" data-full="{{ $fullPath }}" class="thumbnail rounded shadow cursor-pointer" style="max-width: 100px; max-height: 100px;" />
                            @elseif (in_array(strtolower($ext), ['mp4', 'webm', 'ogg']))
                                <video src="{{ $fullPath }}" controls data-full="{{ $fullPath }}" class="rounded shadow cursor-pointer" style="max-width: 100px; max-height: 100px;"></video>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Modal Viewer -->
                <div id="mediaModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center hidden z-50">
                    <div class="relative max-w-[90%] max-h-[90%]">
                        <button id="closeModal" class="absolute top-2 right-2 text-white text-2xl font-bold">&times;</button>
                        <img id="modalImage" src="" class="max-w-screen max-h-screen rounded shadow-xl hidden" />
                        <video id="modalVideo" controls class="max-w-screen max-h-screen rounded shadow-xl hidden">
                            <source id="modalVideoSource" src="" type="video/mp4" />
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-Dashboard>


<script>
    // Modal toggle for Add New Window Group
    function toggleModal(show) {
        $('#modal').toggleClass('hidden', !show);
    }

    $(function () {
        // Update Access AJAX
        $('.update-access').on('click', function () {
            const userId = $(this).data('user-id');
            const queueId = $(this).data('queue-id');
            const canCloseOwnWindow = $(`input[data-id="${userId}"][data-field="can_close_own_window"]`).prop('checked');
            const canCloseAnyWindow = $(`input[data-id="${userId}"][data-field="can_close_any_window"]`).prop('checked');
            const canCloseQueue = $(`input[data-id="${userId}"][data-field="can_close_queue"]`).prop('checked');
            const canClearQueue = $(`input[data-id="${userId}"][data-field="can_clear_queue"]`).prop('checked');

            $.ajax({
                url: "{{ route('update-access', ['user_id' => '__userId__', 'queue_id' => '__queueId__']) }}"
                    .replace('__userId__', userId).replace('__queueId__', queueId),
                method: 'POST',
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: JSON.stringify({
                    can_close_own_window: canCloseOwnWindow,
                    can_close_any_window: canCloseAnyWindow,
                    can_close_queue: canCloseQueue,
                    can_clear_queue: canClearQueue
                }),
                success: function (data) {
                    alert(data.success ? 'Access privileges updated successfully.' : 'Failed to update access privileges.');
                },
                error: function () {
                    alert('An error occurred while updating access privileges.');
                }
            });
        });

        // Media Modal Preview
        $('#preview').on('click', '.thumbnail, video', function () {
            const fullPath = $(this).data('full') || $(this).attr('src');
            const isVideo = /\.(mp4|webm|ogg)$/i.test(fullPath);

            $('#mediaModal').removeClass('hidden');
            if (isVideo) {
                $('#modalImage').addClass('hidden').attr('src', '');
                $('#modalVideoSource').attr('src', fullPath);
                $('#modalVideo').removeClass('hidden')[0].load();
            } else {
                $('#modalVideo').addClass('hidden')[0].pause();
                $('#modalVideoSource').attr('src', '');
                $('#modalImage').attr('src', fullPath).removeClass('hidden');
            }
        });

        // Close media modal
        $('#closeModal').on('click', function () {
            $('#mediaModal').addClass('hidden');
            $('#modalVideo')[0].pause();
        });
        $('#mediaModal').on('click', function (e) {
            if (e.target.id === 'mediaModal') {
                $(this).addClass('hidden');
                $('#modalVideo')[0].pause();
            }
        });

        // File input preview and upload
        const $fileInput = $('#image');
        const $uploadMessage = $('#uploadMessage');
        const $preview = $('#preview');

        $fileInput.on('change', function () {
            const files = this.files;
            if (!files.length) return;

            // Preview selected files
            $preview.empty();
            $.each(files, function (i, file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const url = e.target.result;
                    const ext = file.name.split('.').pop().toLowerCase();
                    let $el;
                    if (['mp4', 'webm', 'ogg'].includes(ext)) {
                        $el = $('<video controls>').attr('src', url)
                            .addClass('rounded shadow cursor-pointer')
                            .css({ maxWidth: '100px', maxHeight: '100px' });
                    } else {
                        $el = $('<img>').attr('src', url)
                            .addClass('rounded shadow cursor-pointer thumbnail')
                            .css({ maxWidth: '100px', maxHeight: '100px' });
                    }
                    $preview.append($el);
                };
                reader.readAsDataURL(file);
            });

            // Upload files via AJAX
            $uploadMessage.removeClass('hidden');
            const formData = new FormData();
            $.each(files, function (i, file) {
                if (file.size > 50 * 1024 * 1024) {
                    alert(`"${file.name}" is too large.`);
                    $uploadMessage.addClass('hidden');
                    return false;
                }
                formData.append('File[]', file);
            });
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('queue.advertisement', ['id' => $queue->id]) }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (!data.success) {
                        alert(data.message || 'Upload failed.');
                        return;
                    }
                    $preview.empty();
                    $.each(data.media_urls || [], function (i, path) {
                        const ext = path.split('.').pop().toLowerCase();
                        let $el;
                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                            $el = $('<img>').attr('src', path)
                                .attr('data-full', path)
                                .addClass('rounded shadow cursor-pointer thumbnail')
                                .css({ maxWidth: '100px', maxHeight: '100px' });
                        } else if (['mp4', 'webm', 'ogg'].includes(ext)) {
                            $el = $('<video controls>')
                                .attr('src', path)
                                .attr('data-full', path)
                                .addClass('rounded shadow cursor-pointer')
                                .css({ maxWidth: '100px', maxHeight: '100px' });
                        }
                        if ($el) $preview.append($el);
                    });
                },
                error: function () {
                    alert("Error uploading files. Please try again.");
                },
                complete: function () {
                    $uploadMessage.addClass('hidden');
                }
            });
        });
    });
</script>
