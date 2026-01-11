<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-12 sm:ml-64 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="mb-10">
                    <h1 class="text-3xl font-bold text-gray-900">My Assigned Queues</h1>
                    <p class="mt-2 text-lg text-gray-600">Access and manage the queues and windows assigned to you.</p>
                </div>

                <!-- Windows Section (Primary Action) -->
                <div class="mb-12">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Your Windows</h2>
                            <p class="text-sm text-gray-500 mt-1">Select a window to start serving tickets.</p>
                        </div>
                    </div>
                    
                    @if ($windows->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($windows as $window)
                                <a href="{{ route('QueuingDashboard', ['id' => $window->id]) }}" 
                                   class="group block bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-green-200 transition-all duration-200 overflow-hidden">
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="bg-green-100 text-green-700 p-2 rounded-lg group-hover:bg-green-600 group-hover:text-white transition-colors duration-200">
                                                <span class="material-symbols-outlined" style="font-size: 24px;">desktop_windows</span>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $window->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $window->status === 'open' ? 'bg-green-600' : 'bg-red-600' }}"></span>
                                                {{ ucfirst($window->status) }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-700 transition-colors">{{ $window->name }}</h3>
                                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center text-sm text-gray-500">
                                            <span class="material-symbols-outlined mr-2" style="font-size: 18px;">layers</span>
                                            <span>Queue: {{ $window->queue->name }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                            <span class="material-symbols-outlined text-gray-400 text-5xl mb-3">desktop_access_disabled</span>
                            <p class="text-gray-900 font-medium">No windows assigned</p>
                            <p class="text-sm text-gray-500 mt-1">You haven't been assigned to any windows yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Queues Management Section -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                         <div>
                            <h2 class="text-xl font-bold text-gray-900">Manage Queues</h2>
                             <p class="text-sm text-gray-500 mt-1">View details and analytics for queues you have access to.</p>
                        </div>
                    </div>

                    @if ($queues->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($queues as $queue)
                                <a href="{{ route('queue.manage', ['id' => $queue->id]) }}" 
                                   class="group block bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-green-200 transition-all duration-200">
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-4">
                                             <div class="bg-blue-50 text-blue-600 p-2 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors duration-200">
                                                <span class="material-symbols-outlined" style="font-size: 24px;">schedule</span>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $queue->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $queue->status === 'open' ? 'bg-green-600' : 'bg-red-600' }}"></span>
                                                {{ ucfirst($queue->status) }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-700 transition-colors">{{ $queue->name }}</h3>
                                        <p class="text-sm text-gray-500 mt-2 line-clamp-2">Click to view queue details, settings, and analytics.</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                            <span class="material-symbols-outlined text-gray-400 text-5xl mb-3">playlist_remove</span>
                            <p class="text-gray-900 font-medium">No queues available</p>
                            <p class="text-sm text-gray-500 mt-1">You don't have administrative access to any queues.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </x-slot>
</x-Dashboard>
