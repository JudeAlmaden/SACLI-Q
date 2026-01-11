@props(['queue'])
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
    <!-- Live View Link -->
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
            <span class="material-symbols-outlined text-green-600 mr-2 text-[20px]">monitor</span>
            Live View URL
        </label>
        <div class="relative flex items-center">
            <input type="text" readonly value="{{ route('liveQueue', ['code' => $queue->code]) }}" 
                class="block w-full rounded-l-lg border-gray-300 bg-gray-50 text-gray-600 text-sm focus:ring-green-500 focus:border-green-500 py-2.5 px-3">
            <button data-copy="{{ route('liveQueue', ['code' => $queue->code]) }}"
                class="copyButton inline-flex items-center px-4 py-2.5 border border-l-0 border-gray-300 rounded-r-lg bg-white hover:bg-gray-50 text-gray-600 hover:text-green-600 transition-colors"
                title="Copy to clipboard">
                <span class="material-symbols-outlined text-[20px] icon-copy">content_copy</span>
                <span class="material-symbols-outlined text-[20px] icon-check hidden text-green-600">check</span>
            </button>
        </div>
        <p class="text-xs text-gray-400 mt-2">Share this link for public display screens.</p>
    </div>

    <!-- Ticketing Link -->
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
             <span class="material-symbols-outlined text-green-600 mr-2 text-[20px]">confirmation_number</span>
            Ticketing URL
        </label>
        <div class="relative flex items-center">
             <input type="text" readonly value="{{ route('ticketing', ['code' => $queue->code]) }}" 
                class="block w-full rounded-l-lg border-gray-300 bg-gray-50 text-gray-600 text-sm focus:ring-green-500 focus:border-green-500 py-2.5 px-3">
            <button data-copy="{{ route('ticketing', ['code' => $queue->code]) }}"
                class="copyButton inline-flex items-center px-4 py-2.5 border border-l-0 border-gray-300 rounded-r-lg bg-white hover:bg-gray-50 text-gray-600 hover:text-green-600 transition-colors"
                title="Copy to clipboard">
                <span class="material-symbols-outlined text-[20px] icon-copy">content_copy</span>
                 <span class="material-symbols-outlined text-[20px] icon-check hidden text-green-600">check</span>
            </button>
        </div>
         <p class="text-xs text-gray-400 mt-2">Share this link for users to get tickets.</p>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.copyButton').forEach(button => {
            button.addEventListener('click', function () {
                const textToCopy = this.getAttribute('data-copy');
                const iconCopy = this.querySelector('.icon-copy');
                const iconCheck = this.querySelector('.icon-check');

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(textToCopy).then(() => {
                        showSuccess(iconCopy, iconCheck);
                    }).catch(err => {
                        console.error('Copy failed:', err);
                    });
                } else {
                    // Fallback
                    const tempInput = document.createElement('input');
                    tempInput.value = textToCopy;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    try {
                        document.execCommand('copy');
                        showSuccess(iconCopy, iconCheck);
                    } catch (err) {
                        console.error('Copy failed:', err);
                    }
                    document.body.removeChild(tempInput);
                }
            });
        });

        function showSuccess(iconCopy, iconCheck) {
            iconCopy.classList.add('hidden');
            iconCheck.classList.remove('hidden');

            setTimeout(() => {
                iconCheck.classList.add('hidden');
                iconCopy.classList.remove('hidden');
            }, 2000);
        }
    });
</script>