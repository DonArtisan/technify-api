<nav id="sidebar" class="md:left-0 md:block md:fixed md:top-0 md:bottom-0 md:overflow-y-auto md:flex-row md:flex-nowrap md:overflow-hidden shadow-xl bg-white flex flex-wrap items-center justify-between relative md:w-64 z-10 py-4 px-6">
    <div class="md:flex-col md:items-stretch md:min-h-full md:flex-nowrap px-0 flex flex-wrap items-center justify-between w-full mx-auto">
        <button class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent" type="button" data-navbar data-target="example-collapse-sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <a class="md:block text-left md:pb-2 text-slate-600 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0" href="#">
            Dashboard
        </a>
        <ul class="md:hidden items-center flex flex-wrap list-none">
            <li class="inline-block relative">
                <a class="text-slate-500 block py-1 px-3" href="#" data-dropdown data-target="notification-dropdown">
                    <i class="fas fa-bell"></i>
                </a>
                <div class="hidden bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48" id="notification-dropdown">
                    <a href="#" class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-slate-700">
                        Action
                    </a>
                    <a href="#" class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-slate-700">
                        Another action
                    </a>
                    <a href="#" class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-slate-700">
                        Something
                        else here
                    </a>
                    <div class="h-0 my-2 border border-solid border-slate-100"></div>
                    <a href="#" class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-slate-700">
                        Separated link
                    </a>
                </div>
            </li>
            <li class="inline-block relative">
                <a class="text-slate-500 block" href="#" data-dropdown data-target="user-responsive-dropdown">
                    <div class="items-center flex">
                        <span class="w-12 h-12 text-sm text-white bg-slate-200 inline-flex items-center justify-center rounded-full"><img alt="..." class="w-full rounded-full align-middle border-none shadow-lg" src="https://avatars.githubusercontent.com/u/63825298?v=4" /></span>
                    </div>
                </a>
                <div class="hidden bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48" id="user-responsive-dropdown">
                    <a href="#" class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-slate-700">
                        Action
                    </a>
                    <a href="#" class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-slate-700">
                        Another action
                    </a>
                    <a href="#" class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-slate-700">
                        Something else here
                    </a>
                    <div class="h-0 my-2 border border-solid border-slate-100"></div>
                    <a href="#" class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-slate-700">
                        Separated link
                    </a>
                </div>
            </li>
        </ul>
        <div class="md:flex md:flex-col md:items-stretch md:opacity-100 md:relative md:mt-4 md:shadow-none shadow absolute top-0 left-0 right-0 z-40 overflow-y-auto overflow-x-hidden h-auto items-center flex-1 rounded hidden" id="example-collapse-sidebar">
            <div class="md:min-w-full md:hidden block pb-4 mb-4 border-b border-solid border-slate-200">
                <div class="flex flex-wrap">
                    <div class="w-6/12">
                        <a class="md:block text-left md:pb-2 text-slate-600 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0" href="#">
                            Dashboard
                        </a>
                    </div>
                    <div class="w-6/12 flex justify-end">
                        <button type="button" class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent" data-navbar data-target="example-collapse-sidebar">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <form class="mt-6 mb-4 md:hidden">
                <div class="mb-3 pt-0">
                    <input type="text" placeholder="Search" class="border-0 px-3 py-2 h-12 border border-solid border-slate-500 placeholder-slate-300 text-slate-600 bg-white rounded text-base leading-snug shadow-none outline-none focus:outline-none w-full font-normal" />
                </div>
            </form>
            <!-- Divider -->
            <hr class="my-4 md:min-w-full" />
            <!-- Heading -->
            <h6 class="md:min-w-full text-slate-500 text-xs uppercase font-bold block pt-1 pb-4 no-underline">
                Main
            </h6>
            <!-- Navigation -->

            <ul class="md:flex-col md:min-w-full flex flex-col list-none">
                <li class="items-center">
                    <a href="{{ action([\App\Http\Controllers\DashboardController::class, 'index']) }}" @class(["text-xs uppercase py-3 font-bold block hover:text-pink-600", 'link-active' => request()->routeIs('dashboard')])>
                        <i class="fas fa-tv mr-2 text-sm opacity-75"></i>
                        Dashboard
                    </a>
                </li>

                <li class="items-center">
                    <span data-dropdown data-target="admin" @class(["dropdown cursor-pointer text-xs uppercase py-3 font-bold block hover:text-pink-600", 'link-active' => request()->routeIs('sellers')])>
                        <span>
                            <i class="fas fa-wrench mr-2 text-sm opacity-75"></i>
                            Administración del sistema
                        </span>
                        <span><i class="fas fa-chevron-down mr-2 text-sm opacity-75"></i></span>
                    </span>
                    <ul class="dropdown-item hidden" data-id="admin" id="admin">
                        <li>
                            <a href="{{ action(\App\Http\Livewire\Sellers::class) }}"
                                @class([
                                    "text-xs uppercase py-3 font-bold block text-slate-700 hover:text-pink-600",
                                    'link-active' => request()->routeIs('sellers')
                                ])
                            >
                                <i class="fas fa-users mr-2 text-sm opacity-75"></i>
                                Sellers
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="items-center">
                     <span data-dropdown data-target="sales" @class(["dropdown cursor-pointer text-xs uppercase py-3 font-bold block hover:text-pink-600", 'link-active' => request()->routeIs('customers')])>
                        <span>
                            <i class="fas fa-wrench mr-2 text-sm opacity-75"></i>
                            Venta
                        </span>
                        <span><i class="fas fa-chevron-down"></i></span>
                    </span>
                    <ul class="dropdown-item hidden" data-id="sales" id="sales">
                        <li class="items-center">
                            <a href="#"
                                @class([
                                    "text-xs uppercase py-3 font-bold block text-slate-700 hover:text-pink-600",
                                    'link-active' => request()->routeIs('customers')
                                ])
                            >
                                <i class="fa-regular fa-user mr-2 text-sm"></i>
                                Customers
                            </a>
                        </li>
                        <li class="items-center">
                            <a href="{{ action(\App\Http\Livewire\Products::class) }}"
                                @class([
                                    "text-xs uppercase py-3 font-bold block text-slate-700 hover:text-pink-600",
                                    'link-active' => request()->routeIs('products')
                                ])
                            >
                                <i class="fa-brands fa-product-hunt mr-2 text-sm"></i>
                                Products
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="items-center">
                     <span data-dropdown data-target="catalog" @class(["dropdown cursor-pointer text-xs uppercase py-3 font-bold block hover:text-pink-600", 'link-active' => request()->routeIs('customers')])>
                        <span>
                            <i class="fas fa-pager mr-2 text-sm opacity-75"></i>
                            Catalogo
                        </span>
                        <span><i class="fas fa-chevron-down"></i></span>
                    </span>
                    <ul class="dropdown-item hidden" data-id="catalog" id="catalog">
                        <li class="items-center">
                            <a href="{{ action(\App\Http\Livewire\Brands::class) }}"
                                @class([
                                    "text-xs uppercase py-3 font-bold block text-slate-700 hover:text-pink-600",
                                    'link-active' => request()->routeIs('brand')
                                ])
                            >
                                <i class="fab fa-bandcamp mr-2 text-sm opacity-75"></i>
                                Marcas
                            </a>
                        </li>
                        <li class="items-center">
                            <a href="{{ action(\App\Http\Livewire\Categories::class) }}"
                                @class([
                                    "text-xs uppercase py-3 font-bold block text-slate-700 hover:text-pink-600",
                                    'link-active' => request()->routeIs('categories')
                                ])
                            >
                                <i class="fas fa-braille mr-2 text-sm opacity-75"></i>
                                Categorías
                            </a>
                        </li>
                        <li class="items-center">
                            <a href="{{ action(\App\Http\Livewire\Colors::class) }}"
                                @class([
                                    "text-xs uppercase py-3 font-bold block text-slate-700 hover:text-pink-600",
                                    'link-active' => request()->routeIs('colors')
                                ])
                            >
                                <i class="fas fa-palette mr-2 text-sm opacity-75"></i>
                                Colores
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- Divider -->
            <hr class="my-4 md:min-w-full" />
            <!-- Heading -->
            <h6 class="md:min-w-full text-slate-500 text-xs uppercase font-bold block pt-1 pb-4 no-underline">
                Config
            </h6>
            <!-- Navigation -->

            <ul class="md:flex-col md:min-w-full flex flex-col list-none md:mb-4">
                <li class="items-center">
                    <a href="#" class="text-slate-700 hover:text-pink-600 text-xs uppercase py-3 font-bold block">
                        <i class="fas fa-user text-slate-300 mr-2 text-sm"></i>
                        Profile
                    </a>
                </li>

                <li class="items-center">
                    <a href="#" class="text-slate-700 hover:text-pink-600 text-xs uppercase py-3 font-bold block">
                        <i class="fas fa-tools text-slate-300 mr-2 text-sm"></i>
                        Settings
                    </a>
                </li>

                <li class="items-center">
                    <form action="{{ action([App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy']) }}" method="post">
                        @csrf
                        <button class="text-slate-700 hover:text-pink-600 text-xs uppercase py-3 font-bold block" type="submit">
                            <i class="fas fa-user-circle text-slate-300 mr-2 text-sm"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
