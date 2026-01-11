<head>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<div>
    <aside id="default-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-white border-r border-gray-200 shadow-sm"
        aria-label="Sidebar">

        <div class="flex flex-col h-full overflow-y-auto py-6 px-3">
            <!-- Logo area or top spacing -->
            <div class="mb-8 px-2 flex items-center">
                 <span class="text-2xl font-bold text-green-700 tracking-tight">Sacli-Q</span>
            </div>

            <ul class="space-y-2">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-50 group transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-green-100 text-green-700' : '' }}">
                        <span class="material-symbols-outlined text-gray-500 group-hover:text-green-700 transition duration-200 {{ request()->routeIs('dashboard') ? 'text-green-700' : '' }}">
                            dashboard
                        </span>
                        <span class="ml-3 font-medium">Homepage</span>
                    </a>
                </li>
            </ul>

            @if(session('access_type') === 'admin')
                <div class="mt-8 mb-2 px-2">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin</h3>
                </div>
                <ul class="space-y-2">
                    <li>
                        <a href="{{route('user.list')}}"
                            class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-50 group transition duration-200 {{ request()->routeIs('user.list') ? 'bg-green-100 text-green-700' : '' }}">
                            <span class="material-symbols-outlined text-gray-500 group-hover:text-green-700 transition duration-200 {{ request()->routeIs('user.list') ? 'text-green-700' : '' }}">
                                account_circle
                            </span>
                            <span class="ml-3 font-medium">Manage Users</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('admin.queue.list')}}"
                            class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-50 group transition duration-200 {{ request()->routeIs('admin.queue.list') ? 'bg-green-100 text-green-700' : '' }}">
                            <span class="material-symbols-outlined text-gray-500 group-hover:text-green-700 transition duration-200 {{ request()->routeIs('admin.queue.list') ? 'text-green-700' : '' }}">
                                schedule
                            </span>
                            <span class="ml-3 font-medium">Queues</span>
                        </a>
                    </li>
                </ul>
            @endif

            <div class="mt-8 mb-2 px-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Information</h3>
            </div>
            <ul class="space-y-2">
                <li>
                    <a href="{{route('myQueues')}}"
                        class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-50 group transition duration-200 {{ request()->routeIs('myQueues') ? 'bg-green-100 text-green-700' : '' }}">
                        <span class="material-symbols-outlined text-gray-500 group-hover:text-green-700 transition duration-200 {{ request()->routeIs('myQueues') ? 'text-green-700' : '' }}">
                            edit_note
                        </span>
                        <span class="ml-3 font-medium">My Assigned Queues</span>
                    </a>
                </li>
            </ul>

            <div class="mt-auto pt-8 border-t border-gray-200">
                <a href="{{route('logout')}}"
                    class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-red-50 group transition duration-200">
                    <span class="material-symbols-outlined text-gray-500 group-hover:text-red-600 transition duration-200">
                        power_settings_new
                    </span>
                    <span class="ml-3 font-medium group-hover:text-red-600 transition duration-200">Log out</span>
                </a>
            </div>

        </div>
    </aside>
</div>