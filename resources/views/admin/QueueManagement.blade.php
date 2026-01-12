<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-6 sm:ml-64 bg-gray-50 min-h-screen mb-32">
            
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
                <div class="border-b border-gray-100">
                    <ul class="flex flex-wrap -mb-px px-4 gap-2" id="tabs">
                        <li>
                            <a href="#" class="group inline-flex items-center px-4 py-4 border-b-2 border-transparent hover:text-green-600 hover:border-green-300 font-medium text-sm text-gray-500 transition-all duration-200 active-tab border-green-600 text-green-600" data-tab="analytics">
                                <span class="material-symbols-outlined mr-2 text-[20px]">analytics</span>
                                Analytics
                            </a>
                        </li>
                        <li>
                            <a href="#" class="group inline-flex items-center px-4 py-4 border-b-2 border-transparent hover:text-green-600 hover:border-green-300 font-medium text-sm text-gray-500 transition-all duration-200" data-tab="windows">
                                <span class="material-symbols-outlined mr-2 text-[20px]">desktop_windows</span>
                                Windows
                            </a>
                        </li>
                        <li>
                            <a href="#" class="group inline-flex items-center px-4 py-4 border-b-2 border-transparent hover:text-green-600 hover:border-green-300 font-medium text-sm text-gray-500 transition-all duration-200" data-tab="access">
                                <span class="material-symbols-outlined mr-2 text-[20px]">manage_accounts</span>
                                User Access
                            </a>
                        </li>
                        <li>
                            <a href="#" class="group inline-flex items-center px-4 py-4 border-b-2 border-transparent hover:text-green-600 hover:border-green-300 font-medium text-sm text-gray-500 transition-all duration-200" data-tab="ads">
                                <span class="material-symbols-outlined mr-2 text-[20px]">ad_units</span>
                                Advertisements
                            </a>
                        </li>
                        <li>
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

        <!-- Modal For creating a new window -->
        <div id="modal" class="fixed inset-0 z-50 hidden" style="z-index: 99999;">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="toggleModal('modal')"></div>
            
            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md relative z-10 transform transition-all scale-100">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-900">Add New Window Group</h2>
                        <button onclick="toggleModal('modal')" class="text-gray-400 hover:text-gray-500 transition">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    
                    <form action="{{ route('admin.window.create', ['id' => $queue->id]) }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" id="name" name="name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                placeholder="e.g., Cashier 1"
                                required>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                rows="3" 
                                placeholder="Briefly describe what this window handles..."
                                required></textarea>
                        </div>
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" onclick="toggleModal('modal')"
                                class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white hover:bg-green-700 rounded-lg font-medium transition-colors shadow-sm">
                                Create Window Group
                            </button>
                        </div>
                    </form>
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
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

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
