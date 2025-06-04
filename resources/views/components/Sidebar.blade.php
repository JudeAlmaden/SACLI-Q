<head>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<div>
    <aside id="default-sidebar"
        class="shadow-lg fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 px-3  bg-[#f8f9fa]"
        aria-label="Sidebar">

        <div class="mt-24">
            <a href="{{ route('dashboard') }}"
                class="group flex items-center px-4 py-3 text-sm text-black rounded hover:bg-[#e5ffc2]  hover:text-green-900 transform hover:transition-all duration-700 ease-in-out">
                <span
                    class="material-symbols-outlined mr-4 group-hover:-translate-x-[-7px] transition-all duration-700 ease-in-out"
                    style="font-variation-settings: 'wght' 300;">
                    dashboard
                </span>
                <span class="group-hover:-translate-x-[-7px] transition-all duration-700 ease-in-out">Homepage</span>
            </a>
        </div>

        
        @if(session('access_type') === 'admin')
            <h2 class="mt-[20px] ml-5 font-bold text-base">Admin</h2>
            <ul class="mt-1">
                <li>
                    <a href="{{route('user.list')}}"
                        class="group flex items-center px-4 py-3 text-sm text-black rounded hover:bg-[#e5ffc2] hover:text-green-900 transform hover:transition-all duration-700 ease-in-out">
                        <span
                            class="group material-symbols-outlined mr-4 group-hover:-translate-x-[-7px] transition-all duration-700 ease-in-out"
                            style="font-variation-settings: 'wght' 300;">
                            account_circle
                        </span>
                        <span class="group-hover:-translate-x-[-7px] transition-all duration-700 ease-in-out">Manage
                            Users</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.queue.list')}}"
                        class="group flex items-center px-4 py-3 text-sm text-black rounded hover:bg-[#e5ffc2] hover:text-green-900 transform hover:transition-all duration-700 ease-in-out">
                        <span
                            class="group material-symbols-outlined mr-4 group-hover:-translate-x-[-7px] transition-all duration-700 ease-in-out"
                            style="font-variation-settings: 'wght' 300;">
                            schedule
                        </span>
                        <span class="group-hover:-translate-x-[-7px] transition-all duration-700 ease-in-out">Queues</span>
                    </a>
                </li>
            </ul>
        @endif

        <h2 class="mt-[30px] ml-5 font-bold text-normal">Information</h2>
        <ul class="mt-1">

            <li>
                <a href="{{route('myQueues')}}"
                    class="group flex items-center px-4 py-3 text-sm text-black rounded hover:bg-[#e5ffc2] hover:text-green-900 transform hover:transition-all duration-700 ease-in-out">
                    <span
                        class="group material-symbols-outlined mr-4 group-hover:-translate-x-[-7px] transition-all duration-700 ease-in-out"
                        style="font-variation-settings: 'wght' 300;">
                        edit_note
                    </span>
                    <span class="group-hover:-translate-x-[-7px] transition-all duration-700 ease-in-out">My Assigned
                        Queues and Windows</span>
                </a>
            </li>

        </ul>

        <div class="mt-[40px]">
            <h2 class="mt-[30px] ml-5 font-bold text-normal">Actions</h2>

            <ul class="mt-4">
                <a href="{{route('logout')}}"
                    class="group flex items-center px-4 py-3 text-sm text-black rounded hover:bg-[#ffd7d7] hover:text-green-900 transform hover:transition-all duration-700 ease-in-out">
                    <span
                        class="group material-symbols-outlined mr-4 group-hover:-translate-x-[-7px] transition-all duration-700 ease-in-out"
                        style="font-variation-settings: 'wght' 300;">
                        power_settings_new
                    </span>
                    <span class="group-hover:-translate-x-[-7px] transition-all duration-700 ease-in-out">Log out</span>
                </a>
        </div>

    </aside>
</div>