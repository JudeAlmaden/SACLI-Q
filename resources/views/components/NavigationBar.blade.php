<!-- eff1ed -->
<nav class="fixed top-0 z-50 w-full border-b border-gray-300 shadow-md bg-[#2C6700]">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end ml-2">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <a class="flex ms-2 md:me-24">
                    <span class="self-center text-xl font-bold sm:text-xl whitespace-nowrap text-[b8f500]">SACLI</span>
                    <p class="p-1 font-light text-white self-center text-xl sm:text-xl whitespace-nowrap">Queue</p>
                </a>
            </div>
            <!-- <div class="flex items-center">
                <div class="flex items-center ms-3">
                    <div>
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user" id="user-menu-button">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full" src="https://th.bing.com/th/id/R.3acddd96809373e254a8e6a0b5939754?rik=CfHBzmOotm%2fhog&riu=http%3a%2f%2fwww.pngall.com%2fwp-content%2fuploads%2f5%2fUser-Profile-PNG-Free-Download.png&ehk=Y4PdD7AE%2fHJpnZsPko97b8LANnHWtZJ1GIfmuNyuY2M%3d&risl=&pid=ImgRaw&r=0" alt="user photo">
                        </button>
                    </div>
                    <div class="fixed top-10 right-10 z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm text-gray-900 dark:text-white" role="none">
                                @if(session()->has('name'))
                                    Welcome, {{ session('name') }}!
                                @endif
                            </p>
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                @if(session()->has('account_id'))
                                 {{ session('account_id') }}
                                @endif
                            </p>
                        </div>
                        <ul class="py-1" role="none"> 
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Sign out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var userMenuButton = document.getElementById('user-menu-button');
        var dropdown = document.getElementById('dropdown-user');

        userMenuButton.addEventListener('click', function(event) {
            event.stopPropagation();
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', function(event) {
            var isClickInside = dropdown.contains(event.target);
            if (!isClickInside && event.target !== userMenuButton) {
                dropdown.classList.add('hidden');
            }
        });
    });
</script>