<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/TicketReceipt.blade.php -->
<x-App>
    <x-slot name="content">
        <!-- Main Container with Animated Background -->
        <div class="min-h-screen relative overflow-hidden bg-green-500 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
            
            <!-- Animated Background Blobs -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-green-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
                <div class="absolute top-0 right-1/4 w-96 h-96 bg-emerald-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
                <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-teal-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
            </div>

            <div class="relative z-10 sm:mx-auto sm:w-full sm:max-w-xl"
                 x-data="{ show: false }" 
                 x-init="setTimeout(() => show = true, 100)"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 translate-y-10"
                 x-transition:enter-end="opacity-100 translate-y-0">
                 
                <!-- Ticket Card -->
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/50 relative">
                    <!-- Top Decorative Strip -->
                    <div class="h-3 bg-gradient-to-r from-green-500 via-lime-500 to-green-600"></div>

                    <div class="px-8 py-10 text-center">
                        <div class="mb-6 inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 mb-6 animate-bounce">
                            <i class="fa-solid fa-check text-3xl"></i>
                        </div>

                        <h1 class="text-3xl font-black text-gray-900 mb-2">Ticket Generated!</h1>
                        <p class="text-gray-500 mb-8">Please save a screenshot of your ticket.</p>

                        <!-- The Actual Ticket Visual -->
                        <div class="bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300 p-8 relative overflow-hidden group hover:border-green-300 transition-colors duration-300">
                            <!-- Cutout Circles for effect -->
                            <div class="absolute top-1/2 -left-3 w-6 h-6 bg-white rounded-full"></div>
                            <div class="absolute top-1/2 -right-3 w-6 h-6 bg-white rounded-full"></div>

                            <div class="space-y-1 mb-6">
                                <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">Your Ticket Code</span>
                                <div class="text-6xl sm:text-7xl font-black text-gray-900 tracking-tighter tabular-nums text-green-600">
                                    {{ $Ticket->code }}
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 text-left border-t border-gray-200 pt-6">
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase">Name</span>
                                    <span class="block text-lg font-bold text-gray-800 truncate" title="{{ $Ticket->name }}">{{ $Ticket->name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-span-2">
                                    <span class="block text-xs font-bold text-gray-400 uppercase">Service Window</span>
                                    <span class="block text-lg font-bold text-gray-800">{{ $Ticket->window->name }}</span>
                                </div>
                                <div class="col-span-2">
                                     <span class="block text-xs font-bold text-gray-400 uppercase">Your Position</span>
                                     <span class="block text-lg font-bold text-gray-800">#{{ $Position ?? 'N/A' }} in queue</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <a href="{{ route('ticketing', ['code' => $Queue->code]) }}" 
                               class="group relative w-full inline-flex justify-center py-4 px-6 border border-transparent text-lg font-bold rounded-xl text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all shadow-lg active:scale-[0.98] overflow-hidden">
                                <span class="absolute top-0 left-0 w-full h-full bg-white/10 transform -translate-x-full skew-x-12 group-hover:animate-shimmer"></span>
                                <span class="relative flex items-center gap-2">
                                    <i class="fa-solid fa-arrow-left"></i> Back to Queue Selection
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="mt-8 text-center" 
                     x-show="show" 
                     x-transition:enter="transition ease-out duration-1000 delay-500" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100">
                    <p class="text-sm text-gray-800 font-bold opacity-80">
                        &copy; {{ date('Y') }} SACLI Queueing System
                    </p>
                </div>
            </div>
        </div>
    </x-slot>
</x-App>

<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    @keyframes shimmer {
        100% { transform: translateX(100%) skewX(12deg); }
    }
    .group-hover\:animate-shimmer:hover {
        animation: shimmer 1s;
    }
</style>