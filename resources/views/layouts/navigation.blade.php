<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('socios') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                @auth
                    <!-- solo muestro la barra de navegación a los de la junta-->
                    @if (Auth::user()->rol === 1)
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-dropdown>
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center mt-4 px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                       <div>Socios</div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('socios')" :active="request()->routeIs('socios')">
                                        {{ __('Listado socios') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('socios.create')" :active="request()->routeIs('socios.create')">
                                        {{ __('Nuevo socio') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('socios.create.admin')" :active="request()->routeIs('socios.create.admin')">
                                        {{ __('Nuevo socio sin padrinos') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                            <x-dropdown>
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center mt-4 px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                       <div>Niños</div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('niños')" :active="request()->routeIs('niños')">
                                        {{ __('Niños') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('niños.create')" :active="request()->routeIs('niños.create')">
                                        {{ __('Nuevo niño') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                            <x-dropdown>
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center mt-4 px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                       <div>Herramientas</div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('email')" :active="request()->routeIs('email')">
                                        {{ __('Envio de emails') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('users.filter')" :active="request()->routeIs('users.filter')">
                                        {{ __('Exportar adultos') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('childrens.filter')" :active="request()->routeIs('childrens.filter')">
                                        {{ __('Exportar niños') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('godfather.show')" :active="request()->routeIs('godfather.show')">
                                        {{ __('Padrinos') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>

                        </div>
                    @endif
                @endauth
            </div>

            <!-- Settings Dropdown -->

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                @auth
                                    <div>{{ Auth::user()->name .' ' . Auth::user()->lastname }}</div>
                                @else
                                    <div>Inicia sesión para poder ver tus datos.</div>
                                @endauth
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        @auth
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Mi perfil') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Cerrar sesión') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        @endauth
                        @guest
                            <x-slot name="content">
                                <x-dropdown-link :href="route('login')">
                                    {{ __('Login') }}
                                </x-dropdown-link>
                            </x-slot>
                        @endguest


                    </x-dropdown>
                </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
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
        @auth
            <!-- solo muestro la barra de navegación a los de la junta-->
            @if (Auth::user()->rol === 1)
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('socios')" :active="request()->routeIs('socios')">
                        {{ __('Socios') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('socios.create')" :active="request()->routeIs('socios.create')">
                        {{ __('Nuevo socio') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('socios.create.admin')" :active="request()->routeIs('socios.create.admin')">
                        {{ __('Nuevo socio sin padrinos') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('niños')" :active="request()->routeIs('niños')">
                        {{ __('Niños') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('niños.create')" :active="request()->routeIs('niños.create')">
                        {{ __('Nuevo niño') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('email')" :active="request()->routeIs('email')">
                        {{ __('Envio de emails') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('users.filter')" :active="request()->routeIs('users.filter')">
                        {{ __('Exportar adultos') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('childrens.filter')" :active="request()->routeIs('childrens.filter')">
                        {{ __('Exportar niños') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('godfather.show')" :active="request()->routeIs('godfather.show')">
                        {{ __('Padrinos') }}
                    </x-responsive-nav-link>

                </div>
            @endif
        @endauth
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                @auth
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                @endauth

                @guest
                    <p>Inicia sesión para poder ver tus datos.</p>
                @endguest

            </div>


            @auth
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Mi perfil') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Cerrar sesión') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @endauth
            @guest
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Login') }}
                    </x-responsive-nav-link>
                </div>
            @endguest
        </div>
    </div>
</nav>
