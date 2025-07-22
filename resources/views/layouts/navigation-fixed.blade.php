<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="/img/logoD.png" alt="Logo" class="block h-9 w-auto" style="filter: drop-shadow(0 2px 6px #0dcaf0aa);" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex align-items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <i class="bi bi-speedometer2 me-1"></i>{{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('dashboard.analytics')" :active="request()->routeIs('dashboard.analytics')">
                        <i class="bi bi-graph-up me-1"></i>Analytics
                    </x-nav-link>

                    <x-nav-link :href="route('relatorios.index')" :active="request()->routeIs('relatorios.*')">
                        <i class="bi bi-file-earmark-bar-graph me-1"></i>Relatórios
                    </x-nav-link>

                    @if(Auth::check() && Auth::user()->is_admin)
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            <i class="bi bi-people-fill me-1"></i><span class="fw-bold">Usuários</span>
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.create')" :active="request()->routeIs('admin.users.create')">
                            <i class="bi bi-person-plus-fill me-1"></i><span class="fw-bold text-success">Novo Usuário</span>
                        </x-nav-link>
                        <x-nav-link :href="route('admin.sugestoes.index')" :active="request()->routeIs('admin.sugestoes.*')">
                            <i class="bi bi-chat-dots-fill me-1"></i><span class="fw-bold">Mensagens</span>
                            @php
                                $totalMensagens = \App\Models\Sugestao::count();
                                $totalContatos = \App\Models\Sugestao::where('tipo','contato')->count();
                            @endphp
                            <span class="badge bg-info ms-1">{{ $totalContatos }}</span>
                            <span class="badge bg-secondary ms-1">{{ $totalMensagens }}</span>
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown - FIXED LAYOUT -->
            <div class="hidden sm:flex sm:items-center sm:ml-3 flex-shrink-0">
                <!-- Compact Control Buttons -->
                <div class="flex items-center space-x-1 mr-2">
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle" type="button" 
                            class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition duration-150 ease-in-out"
                            title="Alternar modo escuro">
                        <svg id="darkModeIcon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 716.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </button>

                    <!-- Notifications Toggle -->
                    <button id="notificationsToggle" type="button" 
                            class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition duration-150 ease-in-out"
                            title="Configurar notificações">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                        </svg>
                        <span id="notificationIndicator" class="hidden absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- Advanced Search Toggle -->
                    <button id="advancedSearchToggle" type="button" 
                            class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition duration-150 ease-in-out"
                            title="Busca avançada">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div class="max-w-32 truncate">{{ Auth::check() ? Auth::user()->name : 'Visitante' }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden space-x-2">
                <!-- Mobile Dark Mode Toggle -->
                <button id="darkModeToggleMobile" type="button" 
                        class="p-2 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 rounded-md transition duration-150 ease-in-out">
                    <svg id="darkModeIconMobile" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 716.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                </button>

                <!-- Mobile Notifications Toggle -->
                <button id="notificationsToggleMobile" type="button" 
                        class="relative p-2 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 rounded-md transition duration-150 ease-in-out">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                    </svg>
                    <span id="notificationIndicatorMobile" class="hidden absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full"></span>
                </button>

                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('dashboard.analytics')" :active="request()->routeIs('dashboard.analytics')">
                Analytics
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('relatorios.index')" :active="request()->routeIs('relatorios.*')">
                Relatórios
            </x-responsive-nav-link>

            @if(Auth::check() && Auth::user()->is_admin)
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    Usuários
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.sugestoes.index')" :active="request()->routeIs('admin.sugestoes.*')">
                    Mensagens
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::check() ? Auth::user()->name : 'Visitante' }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::check() && Auth::user()->email ? Auth::user()->email : '' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
