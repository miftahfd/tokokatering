<header class="z-10 py-4 bg-white shadow-md border-solid border-b-4 border-red-600 dark:bg-gray-800">
    <div class="container flex items-center justify-between h-full px-6 mx-auto">
        <!-- Mobile hamburger -->
        <button class="p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-purple"
            @click="toggleSideMenu" aria-label="Menu">
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
        <div class="grid grid-cols-1 w-full">
            {{-- <div class="flex justify-start flex-1 lg:mr-32">
                Welcome, {{auth()->user()->name}}
            </div> --}}
            <ul class="flex justify-end items-center flex-shrink-0 space-x-6">
                <!-- Dark mode -->
                {{-- <li class="flex">
                    <button class="rounded-md focus:outline-none focus:shadow-outline-purple" @click="toggleTheme"
                        aria-label="Toggle color mode">
                        <template x-if="!dark">
                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                        </template>
                        <template x-if="dark">
                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </template>
                    </button>
                </li> --}}
                <li class="relative">
                    <button class="inline-flex items-center justify-between w-full text-sm font-semibold"
                        @click="toggleSelectRoleMenu" @click.away="closeSelectRoleMenu"
                        @keydown.escape="closeSelectRoleMenu" aria-label="Notifications" aria-haspopup="true">
                        <span class="inline-flex items-center">
                            <span class="mr-1">Sedang Login Sebagai {{$user_logged_active_role->name}}</span>
                        </span>
                        <i class="ti ti-caret-down-filled"></i>
                    </button>
                    <template x-if="isSelectRoleMenuOpen">
                        <ul x-data="selectRole()" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:text-gray-300 dark:border-gray-700 dark:bg-gray-700">
                            @foreach($user_logged_roles as $role)
                                <li class="flex">
                                    <a class="inline-flex items-center justify-between w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200 {{$user_logged_active_role->id == $role->id ? 'bg-gray-100 dark:bg-gray-800' : ''}}"
                                        href="javascript:void(0);" data-role_id="{{$role->id}}" @click.prevent="set($event)">
                                        <span>{{$role->name}}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </template>
                </li>
                <li class="relative">
                    <button class="align-middle rounded-full focus:shadow-outline-purple focus:outline-none"
                        @click="toggleProfileMenu" @click.away="closeProfileMenu" @keydown.escape="closeProfileMenu"
                        aria-label="Account" aria-haspopup="true">
                        <img class="object-cover w-8 h-8 rounded-full"
                            src="/images/user-default.png"
                            aria-hidden="true" />
                    </button>
                    <template x-if="isProfileMenuOpen">
                        <ul x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 dark:bg-gray-700"
                            aria-label="submenu">
                            <li class="flex">
                                <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                                    href="#">
                                    <i class="ti ti-user-circle"></i>
                                    <span class="ml-2">Username : {{auth()->user()->username}}</span>
                                </a>
                            </li>
                            <li class="flex">
                                <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                                    href="#">
                                    <i class="ti ti-user-circle"></i>
                                    <span class="ml-2">{{auth()->user()->name}}</span>
                                </a>
                            </li>
                            <li class="flex" onclick="document.getElementById('logout-form').submit()">
                                <a class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                                    href="#">
                                    <i class="ti ti-logout-2"></i>
                                    <span class="ml-2">Logout</span>
                                    <!-- Authentication -->
                                    <form method="POST" id="logout-form" action="{{ route('logout') }}"> @csrf </form>
                                </a>
                            </li>
                        </ul>
                    </template>
                </li>
            </ul>
        </div>
    </div>
</header>