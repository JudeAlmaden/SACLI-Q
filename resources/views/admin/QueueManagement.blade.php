<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-12 sm:ml-64 bg-white min-h-screen">
            <div class="p-8 bg-gray-50 border border-gray-200 rounded-xl shadow-lg">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $queue->name }}</h1>
                    <p class="text-lg text-gray-500">Manage access and details for this queue.</p>
                </div>

                <!-- Tabs -->
                <div>
                    <ul class="flex border-b text-sm font-medium text-gray-700 mb-6" id="tabs">
                        <li class="-mb-px mr-1">
                            <a href="#" class="inline-block px-4 py-2 border-b-2 border-green-600 active-tab" data-tab="analytics">Analytics</a>
                        </li>
                        <li class="mr-1">
                            <a href="#" class="inline-block px-4 py-2 border-b-2 border-transparent hover:border-gray-300" data-tab="access">User Access</a>
                        </li>
                        <li class="mr-1">
                            <a href="#" class="inline-block px-4 py-2 border-b-2 border-transparent hover:border-gray-300" data-tab="links">Links</a>
                        </li>
                        <li class="mr-1">
                            <a href="#" class="inline-block px-4 py-2 border-b-2 border-transparent hover:border-gray-300" data-tab="windows">Windows</a>
                        </li>
                        <li>
                            <a href="#" class="inline-block px-4 py-2 border-b-2 border-transparent hover:border-gray-300" data-tab="ads">Advertisements</a>
                        </li>
                    </ul>

                    <!-- Tab Panels -->
                    <div id="tab-content">
                        <div class="tab-panel" id="analytics">
                            <x-queue-analytics 
                                :queue="$queue"
                                :analytics="$analytics" 
                                :uniqueUsers="$uniqueUsers" 
                                :aggregatedTickets="$aggregatedTickets"
                            />
                        </div>

                        <div class="tab-panel hidden" id="access">
                            <x-users-with-access 
                                :queue="$queue" 
                                :uniqueUsers="$uniqueUsers" 
                                :accessList="$accessList" 
                                :userWindows="$userWindows"/>
                        </div>

                        <div class="tab-panel hidden" id="links">
                            <x-copy-links :queue="$queue"/>
                        </div>

                        <div class="tab-panel hidden" id="windows">
                            <x-windows-list :queue="$queue"/>
                        </div>

                        <div class="tab-panel hidden" id="ads">
                            <x-upload-advertisement :queue="$queue"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-Dashboard>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('#tabs a');
        const panels = document.querySelectorAll('.tab-panel');

        tabs.forEach(tab => {
            tab.addEventListener('click', function (e) {
                e.preventDefault();

                // Remove active classes
                tabs.forEach(t => t.classList.remove('border-green-600', 'active-tab'));
                panels.forEach(p => p.classList.add('hidden'));

                // Activate selected tab
                const tabId = this.dataset.tab;
                this.classList.add('border-green-600', 'active-tab');
                document.getElementById(tabId).classList.remove('hidden');
            });
        });
    });
</script>
