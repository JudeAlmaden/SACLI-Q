<x-Dashboard>
    <x-slot name="content">
        <div class="p-6 sm:ml-64 min-h-screen bg-gray-50 flex flex-col items-center justify-center">
            
            <div class="max-w-6xl w-full grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                
                <!-- Left Side: Welcome Text -->
                <div class="space-y-6 text-center md:text-left">
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium mb-4">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                        System Online
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        Welcome to <span class="text-green-600">SACLI-Q</span>
                    </h1>
                    
                    <p class="text-lg text-gray-600 leading-relaxed">
                        A seamless and efficient queuing system designed to optimize operations and enhance customer satisfaction at St. Anne College Lucena, Inc.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start pt-4">
                        <a href="{{ route('myQueues') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-green-600 hover:bg-green-700 transition-all shadow-lg hover:shadow-green-500/30">
                            <span class="material-symbols-outlined mr-2">group_work</span>
                            My Queues
                        </a>
                        <a href="{{ route('queue.manage', ['id' => 1]) }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-200 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all shadow-sm hover:shadow-md">
                            <span class="material-symbols-outlined mr-2">dashboard</span>
                            Go to Dashboard
                        </a>
                    </div>
                </div>

                <!-- Right Side: Visual/Illustration -->
                <div class="relative">
                    <div class="absolute -top-10 -right-10 w-72 h-72 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
                    <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
                    
                    <div class="relative bg-white/80 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8 transform rotate-2 hover:rotate-0 transition-transform duration-500">
                        <!-- Mockup of a Queue Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-4">
                            <div class="flex justify-between items-center mb-4">
                                <div class="w-1/2 h-4 bg-gray-200 rounded animate-pulse"></div>
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                </div>
                            </div>
                            <div class="w-3/4 h-8 bg-gray-100 rounded mb-2 animate-pulse"></div>
                            <div class="w-1/2 h-4 bg-gray-50 rounded animate-pulse"></div>
                        </div>

                        <!-- Mockup of Stats -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-green-50 p-4 rounded-xl">
                                <div class="text-green-600 font-bold text-xl mb-1">24</div>
                                <div class="text-xs text-green-800">Waiting</div>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-xl">
                                <div class="text-blue-600 font-bold text-xl mb-1">102</div>
                                <div class="text-xs text-blue-800">Served</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </x-slot>
</x-Dashboard>