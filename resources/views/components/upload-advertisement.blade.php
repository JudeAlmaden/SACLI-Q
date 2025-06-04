@props(['queue'])
<div>
    <!-- Upload Section -->
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-full mx-auto">
        <h2 class="text-2xl font-semibold text-gray-900 mb-1">Advertisement</h2>
        <p class="text-sm text-gray-500 mb-4">You can place your ad content here.</p>

        @csrf

        <div id="uploadMessage" class="hidden flex items-center justify-center mt-4 text-gray-700">
            <svg class="animate-spin h-5 w-5 mr-3 text-blue-600" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
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
                    <img src="{{ $fullPath }}" data-full="{{ $fullPath }}" class="thumbnail rounded shadow cursor-pointer"
                        style="max-width: 200px; max-height: 200px;" />
                @elseif (in_array(strtolower($ext), ['mp4', 'webm', 'ogg']))
                    <video src="{{ $fullPath }}" controls data-full="{{ $fullPath }}" class="rounded shadow cursor-pointer"
                        style="max-width: 200px; max-height: 200px;"></video>
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


<script>
    $(function () {
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
                            .css({ maxWidth: '200px', maxHeight: '200px' });
                    } else {
                        $el = $('<img>').attr('src', url)
                            .addClass('rounded shadow cursor-pointer thumbnail')
                            .css({ maxWidth: '200px', maxHeight: '200px' });
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
                                .css({ maxWidth: '200px', maxHeight: '200px' });
                        } else if (['mp4', 'webm', 'ogg'].includes(ext)) {
                            $el = $('<video controls>')
                                .attr('src', path)
                                .attr('data-full', path)
                                .addClass('rounded shadow cursor-pointer')
                                .css({ maxWidth: '200px', maxHeight: '200px' });
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