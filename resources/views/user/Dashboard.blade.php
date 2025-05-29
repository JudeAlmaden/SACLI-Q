<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-4 sm:ml-64 min-h-screen relative">
            <!-- Blurred Background Image -->
            <div 
                class="absolute inset-0 w-full h-full z-0 overflow-hidden"
            >
                <div 
                    class="absolute inset-0 scale-110 bg-cover bg-center blur-[12px] brightness-75"
                    style="background-image: url('https://scontent.fmnl13-1.fna.fbcdn.net/v/t1.6435-9/59611518_2172312856215167_7931959178644422656_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=cc71e4&_nc_eui2=AeHA2eXAq71QUtqC6T7fmoJJWWEgMYZI495ZYSAxhkjj3rqJGcu18Zxgs33qLLn_CiXTrVg5JQvilGsKCfKgC4cR&_nc_ohc=KEoVJIESKyMQ7kNvwEVGZ7t&_nc_oc=AdnNHL4mdWXSY6agtXKRbx9By_r2ropGGdTdaVdDxMZI_Dh7jfS862bEGYrYbhFnTbxh_Nw4_96S7UXsdM4vTiWl&_nc_zt=23&_nc_ht=scontent.fmnl13-1.fna&_nc_gid=KihFTO1_GWq-8K1Y_LUR1w&oh=00_AfJGwrBcWOfLY67B-m9-XwzpxDpHW-c-NqPg5ExH6yunvQ&oe=685FF77E');">
                </div>
            </div>

            <!-- Foreground Content -->
            <div class="flex flex-col items-center justify-center min-h-screen p-4 relative z-10">
                <div class="max-w-4xl w-full bg-white shadow-2xl rounded-3xl p-10 border border-green-200 relative overflow-hidden">
                    <div class="absolute inset-0 pointer-events-none">
                        <svg class="w-full h-full" viewBox="0 0 400 400" fill="none">
                            <circle cx="350" cy="50" r="80" fill="#b2dfdb" fill-opacity="0.2"/>
                            <circle cx="60" cy="340" r="60" fill="#81c784" fill-opacity="0.15"/>
                        </svg>
                    </div>
                    <h1 class="text-5xl font-extrabold text-green-800 text-center drop-shadow-lg z-10 relative">Welcome to Sacli Queue</h1>
                    <p class="text-xl text-gray-700 mt-6 text-center z-10 relative">
                         A seamless and efficient queuing system designed to improve waiting times and customer satisfaction.<br>
                         Our system ensures smooth operations with a hassle-free experience.
                    </p>
                    <div class="w-full h-2 bg-gradient-to-r from-green-400 via-green-600 to-green-400 mt-8 rounded-full shadow-md z-10 relative"></div>
                </div>
            </div>
        </div>
    </x-slot>
</x-Dashboard>