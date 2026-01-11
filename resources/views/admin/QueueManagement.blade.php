<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-6 sm:ml-64 bg-gray-50 min-h-screen">
            
            <!-- Header -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between mt-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $queue->name }}</h1>
                    <p class="mt-1 text-gray-500">Manage settings, analytics, and access for this queue.</p>
                </div>
                <div class="mt-4 md:mt-0">
                     <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                        Active
                    </span>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                
                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200 bg-gray-50/50">
                    <ul class="flex flex-wrap -mb-px px-4" id="tabs">
                        <li class="mr-2">
                            <a href="#" class="group inline-flex items-center px-4 py-4 border-b-2 border-transparent hover:text-green-600 hover:border-green-300 font-medium text-sm text-gray-500 transition-all duration-200 active-tab border-green-600 text-green-600" data-tab="analytics">
                                <span class="material-symbols-outlined mr-2 text-[20px]">analytics</span>
                                Analytics
                            </a>
                        </li>
                        <li class="mr-2">
                            <a href="#" class="group inline-flex items-center px-4 py-4 border-b-2 border-transparent hover:text-green-600 hover:border-green-300 font-medium text-sm text-gray-500 transition-all duration-200" data-tab="windows">
                                <span class="material-symbols-outlined mr-2 text-[20px]">desktop_windows</span>
                                Windows
                            </a>
                        </li>
                        <li class="mr-2">
                            <a href="#" class="group inline-flex items-center px-4 py-4 border-b-2 border-transparent hover:text-green-600 hover:border-green-300 font-medium text-sm text-gray-500 transition-all duration-200" data-tab="access">
                                <span class="material-symbols-outlined mr-2 text-[20px]">manage_accounts</span>
                                User Access
                            </a>
                        </li>
                        <li class="mr-2">
                            <a href="#" class="group inline-flex items-center px-4 py-4 border-b-2 border-transparent hover:text-green-600 hover:border-green-300 font-medium text-sm text-gray-500 transition-all duration-200" data-tab="ads">
                                <span class="material-symbols-outlined mr-2 text-[20px]">ad_units</span>
                                Advertisements
                            </a>
                        </li>
                        <li class="mr-2">
                            <a href="#" class="group inline-flex items-center px-4 py-4 border-b-2 border-transparent hover:text-green-600 hover:border-green-300 font-medium text-sm text-gray-500 transition-all duration-200" data-tab="links">
                                <span class="material-symbols-outlined mr-2 text-[20px]">link</span>
                                Links
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Tab Panels -->
                <div class="p-6" id="tab-content">
                    <div class="tab-panel animate-fade-in-up" id="analytics">
                        <x-queue-analytics 
                            :queue="$queue"
                            :analytics="$analytics" 
                            :uniqueUsers="$uniqueUsers" 
                            :aggregatedTickets="$aggregatedTickets"
                        />
                    </div>

                    <div class="tab-panel hidden animate-fade-in-up" id="access">
                        <div class="max-w-7xl mx-auto">
                            <x-users-with-access 
                                :queue="$queue" 
                                :uniqueUsers="$uniqueUsers" 
                                :accessList="$accessList" 
                                :userWindows="$userWindows"/>
                        </div>
                    </div>

                    <div class="tab-panel hidden animate-fade-in-up" id="links">
                        <x-copy-links :queue="$queue"/>
                    </div>

                    <div class="tab-panel hidden animate-fade-in-up" id="windows">
                        <x-windows-list :queue="$queue"/>
                    </div>

                    <div class="tab-panel hidden animate-fade-in-up" id="ads">
                        <x-upload-advertisement :queue="$queue"/>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-Dashboard>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out forwards;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('#tabs a');
        const panels = document.querySelectorAll('.tab-panel');

        tabs.forEach(tab => {
            tab.addEventListener('click', function (e) {
                e.preventDefault();

                // Remove active classes
                tabs.forEach(t => t.classList.remove('border-green-600', 'text-green-600', 'active-tab'));
                panels.forEach(p => p.classList.add('hidden'));

                // Activate selected tab
                const tabId = this.dataset.tab;
                this.classList.add('border-green-600', 'text-green-600', 'active-tab');
                document.getElementById(tabId).classList.remove('hidden');
            });
        });
    });
</script>
