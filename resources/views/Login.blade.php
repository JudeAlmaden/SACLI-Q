<x-App>
    <x-slot name="content" x-init="console.log('{{ session('error') }}')">
        <div class="min-h-screen flex bg-gray-50">
            
            <!-- Left Side: Branding/Image (Hidden on mobile) -->
            <div class="hidden lg:flex w-1/2 bg-green-900 relative items-center justify-center overflow-hidden">
                <div class="absolute inset-0 z-0 opacity-20" style="background-image: url('https://sacli.edu.ph/wp-content/uploads/2023/02/about-us-header.jpg'); background-size: cover; background-position: center;"></div>
                <div class="relative z-10 text-center p-12">
                    <div class="w-16 h-16 bg-white rounded-2xl mx-auto mb-6 flex items-center justify-center shadow-xl">
                        <span class="text-green-700 font-bold text-3xl">Q</span>
                    </div>
                    <h1 class="text-4xl font-bold text-white mb-4">SACLI-Q</h1>
                    <p class="text-green-100 text-lg max-w-md mx-auto leading-relaxed">
                        Streamline your queuing experience. Efficient, reliable, and easy to manage queueing system for St. Anne College Lucena, Inc.
                    </p>
                </div>
                
                <!-- Decorative Circle -->
                <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-green-800 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-green-600 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-12">
                <div class="w-full max-w-md space-y-8">
                    
                    <!-- Header -->
                    <div class="text-center lg:text-left">
                        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Welcome Back</h2>
                        <p class="mt-2 text-sm text-gray-500">
                            Please sign in to access your dashboard.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                        @csrf
                        
                        <!-- Account ID -->
                        <div class="space-y-1">
                            <label for="account_id" class="block text-sm font-medium text-gray-700">
                                Account ID
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-gray-400 text-[20px]">person</span>
                                </div>
                                <input id="account_id" name="account_id" type="text" autocomplete="account_id" required 
                                    class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg py-3" 
                                    placeholder="Enter your Account ID"
                                    value="{{ old('account_id') }}"
                                    autofocus>
                            </div>
                            @error('account_id')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Password
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="material-symbols-outlined text-gray-400 text-[20px]">lock</span>
                                </div>
                                <input id="password" name="password" type="password" autocomplete="current-password" required 
                                    class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg py-3" 
                                    placeholder="••••••••">
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                Sign in
                            </button>
                        </div>
                    </form>

                    <!-- Alerts -->
                    <div class="mt-6">
                         <x-ErrorAlert />
                         <x-SuccessAlert />
                    </div>
                    
                    <div class="mt-6 text-center text-sm text-gray-400 pb-4">
                        &copy; {{ date('Y') }} SACLI-Q. All rights reserved.
                    </div>

                </div>
            </div>
        </div>

        <!-- Material Symbols (Ensure it's loaded if not already in Layout) -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    </x-slot>
</x-App>
