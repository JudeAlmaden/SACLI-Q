<x-App>
    <x-slot name="content" x-init="console.log('{{ session('error') }}')">
        <div class="relative min-h-screen bg-cover bg-center" style="background-image: url('https://sacli.edu.ph/wp-content/uploads/2023/02/about-us-header.jpg');">
            <div class="absolute inset-0 bg-black bg-opacity-60"></div> <!-- Overlay -->

            <div class="relative z-10 flex items-center justify-center min-h-screen">
                <div class="w-full max-w-md px-4">
                    <div class="backdrop-blur-md bg-white/20 border border-white/30 rounded-2xl shadow-xl p-8 md:p-10 text-white">
                        <div class="text-2xl font-bold mb-6 text-center text-white">{{ __('Login') }}</div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Account ID -->
                            <div class="mb-5">
                                <label for="account_id" class="block text-sm font-semibold mb-2">{{ __('Your Account ID') }}</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i class="fas fa-user text-white text-opacity-60"></i>
                                    </span>
                                    <input id="account_id" type="text" name="account_id" value="{{ old('account_id') }}"
                                        required autocomplete="account_id" autofocus
                                        class="w-full pl-10 pr-3 py-2 rounded-lg text-sm text-white bg-white/10 placeholder-white/50 border border-white/30 focus:ring-2 focus:ring-green-400 focus:outline-none @error('account_id') border-red-400 @enderror" placeholder="Enter your Account ID">
                                </div>
                                @error('account_id')
                                    <div class="mt-2 text-sm text-red-300">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-6">
                                <label for="password" class="block text-sm font-semibold mb-2">{{ __('Password') }}</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i class="fas fa-lock text-white text-opacity-60"></i>
                                    </span>
                                    <input id="password" type="password" name="password" required autocomplete="current-password"
                                        class="w-full pl-10 pr-3 py-2 rounded-lg text-sm text-white bg-white/10 placeholder-white/50 border border-white/30 focus:ring-2 focus:ring-green-400 focus:outline-none @error('password') border-red-400 @enderror" placeholder="Enter your password">
                                </div>
                                @error('password')
                                    <div class="mt-2 text-sm text-red-300">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="w-full py-2 px-4 rounded-lg bg-green-500 hover:bg-green-600 transition-colors duration-200 font-semibold text-sm text-white">
                                    {{ __('Login') }}
                                </button>
                            </div>

                            <!-- Alerts -->

                        </form>                            

                    </div>                        
                    <div class="mt-4">
                        <x-ErrorAlert />
                        <x-SuccessAlert />
                    </div>
                </div>
            </div>
        </div>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </x-slot>
</x-App>
