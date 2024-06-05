<div x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">
</div>
<aside class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-20 overflow-y-auto bg-white dark:bg-gray-800 md:hidden"
    x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0 transform -translate-x-20" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 transform -translate-x-20" @click.away="closeSideMenu"
    @keydown.escape="closeSideMenu">
    <div class="py-4 text-gray-500 dark:text-gray-400">
        <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="{{ route('home') }}">
            {{ config('app.name') }}
        </a>
        <ul class="mt-6">
            <li class="relative px-6 py-3">
                @if (request()->routeIs('home'))
                    <span class="absolute inset-y-0 left-0 w-1 bg-red-600 rounded-tr-lg rounded-br-lg"
                        aria-hidden="true"></span>
                @endif

                <a class="inline-flex items-center w-full font-semibold text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                    href="{{ route('home') }}">
                    <i class="text-lg ti ti-home-2"></i>
                    <span class="text-sm ml-4">Home</span>
                </a>
            </li>
            @can('user.read')
                <li class="relative px-6 py-3">
                    @if (request()->routeIs('user.index') || request()->routeIs('role.index') || request()->routeIs('permission.index'))
                        <span class="absolute inset-y-0 left-0 w-1 bg-red-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"></span>
                    @endif

                    <button
                        class="inline-flex items-center justify-between w-full font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                        @click="toggleUserMenu" aria-haspopup="true">
                        <span class="inline-flex items-center">
                            <i class="text-lg ti ti-settings-2"></i>
                            <span class="text-sm ml-4">Setting</span>
                        </span>
                        <i class="ti ti-caret-down-filled"></i>
                    </button>
                    @if (request()->routeIs('user.index') || request()->routeIs('role.index') || request()->routeIs('permission.index'))
                    <template x-if="true">
                    @else
                    <template x-if="isUserMenuOpen">
                    @endif
                        <ul x-transition:enter="transition-all ease-in-out duration-300"
                            x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl"
                            x-transition:leave="transition-all ease-in-out duration-300"
                            x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                            class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                            aria-label="submenu">
                            <li
                                class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                                <a class="block w-full" href="{{route('user.index')}}">User Management</a>
                            </li>
                            <li
                                class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                                <a class="block w-full" href="{{route('role.index')}}">
                                    Role Management
                                </a>
                            </li>
                            <li
                                class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                                <a class="block w-full" href="{{route('permission.index')}}">
                                    Permission Management
                                </a>
                            </li>
                        </ul>
                    </template>
                </li>
            @endcan
            {{-- @can('qc.read')
                <li class="relative px-6 py-3">
                    @if (request()->routeIs('qc.index'))
                        <span class="absolute inset-y-0 left-0 w-1 bg-red-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"></span>
                    @endif

                    <a class="inline-flex items-center w-full font-semibold text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                        href="{{ route('qc.index') }}">
                        <i class="text-lg ti ti-device-heart-monitor"></i>
                        <span class="text-sm ml-4">QC</span>
                    </a>
                </li>
            @endcan --}}
            @can('log-viewer.read')
                <li class="relative px-6 py-3">
                    @if (request()->routeIs('log-viewer'))
                        <span class="absolute inset-y-0 left-0 w-1 bg-red-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"></span>
                    @endif

                    <a class="inline-flex items-center w-full font-semibold text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                        href="{{ route('log-viewer') }}">
                        <i class="text-lg ti ti-player-record"></i>
                        <span class="text-sm ml-4">Log Viewer</span>
                    </a>
                </li>
            @endcan
        </ul>
    </div>
</aside>