<head>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        /* Sidebar collapse styles */
        #default-sidebar {
            width: 16rem; /* w-64 */
        }
        #default-sidebar.collapsed {
            width: 4rem; /* w-16 */
        }
        #default-sidebar .sidebar-content {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        #default-sidebar.collapsed .sidebar-content {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        #default-sidebar.collapsed .sidebar-text,
        #default-sidebar.collapsed .sidebar-section-title {
            display: none;
        }
        #default-sidebar.collapsed .sidebar-link {
            justify-content: center;
        }
        #default-sidebar .logo-full {
            display: flex;
        }
        #default-sidebar .logo-mini {
            display: none;
        }
        #default-sidebar.collapsed .logo-full {
            display: none;
        }
        #default-sidebar.collapsed .logo-mini {
            display: flex;
        }
        /* External toggle button - fixed positioning */
        #sidebar-toggle-btn {
            position: fixed;
            left: 16rem; /* matches sidebar width (w-64) */
            top: 5.5rem;
            transform: translateX(-50%);
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #16a34a;
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.25);
            cursor: pointer;
            z-index: 50;
            transition: all 0.3s ease;
        }
        #sidebar-toggle-btn:hover {
            background: #15803d;
            transform: translateX(-50%) scale(1.15);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        #sidebar-toggle-btn .material-symbols-outlined {
            font-size: 18px;
            color: white;
        }
        /* Adjust button position when sidebar is collapsed */
        #default-sidebar.collapsed ~ #sidebar-toggle-btn {
            left: 4rem; /* matches collapsed width (w-16) */
        }
        
        /* Main content margin adjustment - when sidebar is collapsed */
        @media (min-width: 640px) {
            body.sidebar-collapsed .sm\:ml-64 {
                margin-left: 4rem !important;
                transition: margin-left 0.3s ease;
            }
            .sm\:ml-64 {
                transition: margin-left 0.3s ease;
            }
        }
    </style>
</head>

<div id="sidebar-wrapper">
    <aside id="default-sidebar"
        class="fixed top-0 left-0 z-40 h-screen transition-all duration-300 -translate-x-full sm:translate-x-0 bg-white border-r border-gray-200 shadow-sm"
        aria-label="Sidebar">

        <div class="sidebar-content flex flex-col h-full overflow-y-auto py-6">
            <!-- Logo area -->
            <div class="logo-full mb-8 px-2 items-center justify-center">
                 <span class="text-2xl font-bold text-green-700 tracking-tight">Sacli-Q</span>
            </div>
            <div class="logo-mini mb-8 px-2 items-center justify-center">
                 <span class="text-xl font-bold text-green-700">S</span>
            </div>

            <ul class="space-y-2">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="sidebar-link flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-50 group transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-green-100 text-green-700' : '' }}">
                        <span class="material-symbols-outlined text-gray-500 group-hover:text-green-700 transition duration-200 {{ request()->routeIs('dashboard') ? 'text-green-700' : '' }}">
                            dashboard
                        </span>
                        <span class="sidebar-text ml-3 font-medium">Homepage</span>
                    </a>
                </li>
            </ul>

            @if(session('access_type') === 'admin')
                <div class="sidebar-section-title mt-8 mb-2 px-2">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin</h3>
                </div>
                <ul class="space-y-2">
                    <li>
                        <a href="{{route('user.list')}}"
                            class="sidebar-link flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-50 group transition duration-200 {{ request()->routeIs('user.list') ? 'bg-green-100 text-green-700' : '' }}">
                            <span class="material-symbols-outlined text-gray-500 group-hover:text-green-700 transition duration-200 {{ request()->routeIs('user.list') ? 'text-green-700' : '' }}">
                                account_circle
                            </span>
                            <span class="sidebar-text ml-3 font-medium">Manage Users</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('admin.queue.list')}}"
                            class="sidebar-link flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-50 group transition duration-200 {{ request()->routeIs('admin.queue.list') ? 'bg-green-100 text-green-700' : '' }}">
                            <span class="material-symbols-outlined text-gray-500 group-hover:text-green-700 transition duration-200 {{ request()->routeIs('admin.queue.list') ? 'text-green-700' : '' }}">
                                schedule
                            </span>
                            <span class="sidebar-text ml-3 font-medium">Queues</span>
                        </a>
                    </li>
                </ul>
            @endif

            <div class="sidebar-section-title mt-8 mb-2 px-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Information</h3>
            </div>
            <ul class="space-y-2">
                <li>
                    <a href="{{route('myQueues')}}"
                        class="sidebar-link flex items-center p-2 text-gray-900 rounded-lg hover:bg-green-50 group transition duration-200 {{ request()->routeIs('myQueues') ? 'bg-green-100 text-green-700' : '' }}">
                        <span class="material-symbols-outlined text-gray-500 group-hover:text-green-700 transition duration-200 {{ request()->routeIs('myQueues') ? 'text-green-700' : '' }}">
                            edit_note
                        </span>
                        <span class="sidebar-text ml-3 font-medium">My Assigned Queues</span>
                    </a>
                </li>
            </ul>

            <div class="mt-auto pt-8 border-t border-gray-200">
                <a href="{{route('logout')}}"
                    class="sidebar-link flex items-center p-2 text-gray-900 rounded-lg hover:bg-red-50 group transition duration-200">
                    <span class="material-symbols-outlined text-gray-500 group-hover:text-red-600 transition duration-200">
                        power_settings_new
                    </span>
                    <span class="sidebar-text ml-3 font-medium group-hover:text-red-600 transition duration-200">Log out</span>
                </a>
            </div>

        </div>
    </aside>

    <!-- Toggle Button OUTSIDE the sidebar with fixed positioning -->
    <button id="sidebar-toggle-btn" type="button" title="Toggle Sidebar">
        <span class="material-symbols-outlined" id="toggle-icon">chevron_left</span>
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('default-sidebar');
    const toggleBtn = document.getElementById('sidebar-toggle-btn');
    const toggleIcon = document.getElementById('toggle-icon');
    const STORAGE_KEY = 'sidebarCollapsed';

    function updateState(isCollapsed) {
        // Update icon
        if (toggleIcon) {
            toggleIcon.textContent = isCollapsed ? 'chevron_right' : 'chevron_left';
        }
        // Update body class for main content margin
        if (isCollapsed) {
            document.body.classList.add('sidebar-collapsed');
        } else {
            document.body.classList.remove('sidebar-collapsed');
        }
    }

    // Load saved state from localStorage
    const savedState = localStorage.getItem(STORAGE_KEY);
    if (savedState === 'true') {
        sidebar.classList.add('collapsed');
        updateState(true);
    }

    // Toggle sidebar on button click
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            
            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem(STORAGE_KEY, isCollapsed);
            updateState(isCollapsed);
        });
    }
});
</script>