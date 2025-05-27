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
                                            <th class="px-6 py-3 text-sm font-medium text-gray-700">Close Any Any</th>
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

                {{-- Ads --}}
                <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-3xl mx-auto">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-1">Advertisement</h2>
                    <p class="text-sm text-gray-500 mb-4">You can place your ad content here.</p>

                    <form action="{{ route('queue.advertisement', ['id' => $queue->id]) }}" method="POST" enctype="multipart/form-data" class="border-2 border-dashed border-gray-300 rounded-lg p-6 flex flex-col gap-4">
                        @csrf

                        <!-- File Input -->
                        <div class="flex justify-center">
                            <label for="image" class="cursor-pointer flex items-center gap-2 text-gray-600 hover:text-gray-800">
                                <span class="material-symbols-outlined text-2xl">image</span>
                                <span>Select Images</span>
                            </label>
                            <input type="file" id="image" name="Images[]" accept="image/*" multiple class="hidden">
                        </div>

                        <!-- Preview Area -->
                        <div id="preview" class="flex flex-wrap gap-3 justify-center">
                            @php
                                $mediaAds = json_decode($queue->media_advertisement ?? '[]', true) ?? [];
                            @endphp
                            @if (!empty($mediaAds))
                                @foreach ($mediaAds as $mediaPath)
                                    @if (!empty($mediaPath))
                                        <img 
                                            src="{{ asset('storage/' . $mediaPath) }}"  
                                            data-full="{{ asset('storage/' . $mediaPath) }}"
                                            class="thumbnail rounded shadow cursor-pointer" 
                                            style="max-width: 100px; max-height: 100px;"
                                        />
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-green-700 text-white rounded-md hover:bg-green-800 transition">Change</button>
                        </div>
                    </form>
                </div>


                {{-- Image preview --}}
                <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center hidden z-50">
                <div class="relative max-w-[90%] max-h-[90%]">
                    <button id="closeModal" class="absolute top-2 right-2 text-white text-2xl font-bold">&times;</button>
                    <img id="modalImage" src="" class="max-w-screen max-h-screen rounded shadow-xl" />
                </div>
                </div>

            </div>
        </div>
    </x-slot>
</x-Dashboard>

                  
<script>
    function toggleModal(show) {
        document.getElementById('modal').classList.toggle('hidden', !show);
    }
</script>
                

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.update-access').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-user-id');
                const queueId = this.getAttribute('data-queue-id');
                const canCloseOwnWindow = document.querySelector(`input[data-id="${userId}"][data-field="can_close_own_window"]`).checked;
                const canCloseAnyWindow = document.querySelector(`input[data-id="${userId}"][data-field="can_close_any_window"]`).checked;
                const canCloseQueue = document.querySelector(`input[data-id="${userId}"][data-field="can_close_queue"]`).checked;
                const canClearQueue = document.querySelector(`input[data-id="${userId}"][data-field="can_clear_queue"]`).checked;

                fetch("{{ route('update-access', ['user_id' => '__userId__', 'queue_id' => '__queueId__']) }}".replace('__userId__', userId).replace('__queueId__', queueId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        can_close_own_window: canCloseOwnWindow,
                        can_close_any_window: canCloseAnyWindow,
                        can_close_queue: canCloseQueue,
                        can_clear_queue: canClearQueue
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Access privileges updated successfully.');
                    } else {
                        alert('Failed to update access privileges.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating access privileges.');
                });
            });
        });
    });
</script>

{{-- Copy Links --}}
<script>
    $(document).ready(function () {
        $('.copyButton').on('click', function () {
            const textToCopy = $(this).data('copy');
            const $statusMessage = $(this).closest('.relative').find('.statusMessage');

            if ($statusMessage.length) {
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
            }
        });
    });
</script>


{{-- Media Preview Functions --}}
<script>
  document.querySelectorAll('.thumbnail').forEach(img => {
    img.addEventListener('click', () => {
      const modal = document.getElementById('imageModal');
      const modalImg = document.getElementById('modalImage');
      modalImg.src = img.dataset.full;
      modal.classList.remove('hidden');
    });
  });

  document.getElementById('closeModal').addEventListener('click', () => {
    document.getElementById('imageModal').classList.add('hidden');
  });

  // Optional: click outside image to close
  document.getElementById('imageModal').addEventListener('click', (e) => {
    if (e.target.id === 'imageModal') {
      e.currentTarget.classList.add('hidden');
    }
  });
</script>

<script>
    const imageInput = document.getElementById('image');
    const preview = document.getElementById('preview');

    imageInput.addEventListener('change', (e) => {
        const files = e.target.files;
        preview.innerHTML = ''; // clear previous images

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = (event) => {
                const image = document.createElement('img');
                image.src = event.target.result;
                image.style.maxWidth = '100px';
                image.style.maxHeight = '100px';
                image.classList.add("rounded", "shadow", "mr-2", "mb-2");
                preview.appendChild(image);
            };

            reader.readAsDataURL(file);
        }
    });
</script>
