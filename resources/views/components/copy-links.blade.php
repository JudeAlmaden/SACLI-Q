@props(['queue'])
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    <div class="relative flex flex-col h-full p-4 border border-gray-300 bg-white rounded-lg shadow">
        <div class="flex items-center gap-2 mb-2">
            <h1 class="text-green-700">URL for live view:</h1>
        </div>
        <div class="flex items-center gap-2">
            <span
                class="text-sm font-mono text-green-800 bg-green-100 px-3 py-2 rounded-md border border-green-300 flex-1 break-all">
                {{ route('liveQueue', ['code' => $queue->code]) }}
            </span>
            <button data-copy="{{ route('liveQueue', ['code' => $queue->code]) }}"
                class="copyButton flex items-center px-2 py-2 bg-green-500 text-white hover:bg-green-600 rounded-md border border-green-500 transition-colors"
                aria-label="Copy to clipboard">
                <!-- Copy Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M8 2a2 2 0 00-2 2v1H5a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2v-1h1a2 2 0 002-2V7a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H8zM7 4a1 1 0 011-1h4a1 1 0 011 1v1H7V4zm8 3v2H5V7h10zM5 12v3h6v-3H5z" />
                </svg>
            </button>
        </div>
        <div
            class="statusMessage absolute top-16 left-4 text-xs text-green-600 bg-green-50 px-3 py-1 rounded-lg border border-green-300 opacity-0 transition-opacity duration-200">
            Copied!
        </div>
    </div>

    <div class="relative flex flex-col h-full p-4 border-b border-gray-300 bg-white rounded-lg shadow">
        <div class="flex items-center gap-2 mb-2">
            <h1 class="text-green-700">URL for ticketing:</h1>
        </div>
        <div class="flex items-center gap-2">
            <span
                class="text-sm font-mono text-green-800 bg-green-100 px-3 py-2 rounded-md border border-green-300 flex-1 break-all">
                {{ route('ticketing', ['code' => $queue->code]) }}
            </span>
            <button data-copy="{{ route('ticketing', ['code' => $queue->code]) }}"
                class="copyButton flex items-center px-2 py-2 bg-green-500 text-white hover:bg-green-600 rounded-md border border-green-600 transition-colors"
                aria-label="Copy to clipboard">
                <!-- Copy Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M8 2a2 2 0 00-2 2v1H5a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2v-1h1a2 2 0 002-2V7a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H8zM7 4a1 1 0 011-1h4a1 1 0 011 1v1H7V4zm8 3v2H5V7h10zM5 12v3h6v-3H5z" />
                </svg>
            </button>
        </div>
        <div
            class="statusMessage absolute top-16 left-4 text-xs text-green-600 bg-green-50 px-3 py-1 rounded-lg border border-green-300 opacity-0 transition-opacity duration-200">
            Copied!
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