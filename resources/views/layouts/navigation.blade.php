<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold">
                        📦 Warehouse App
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:ml-10 sm:flex">
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center px-1 pt-1 text-sm font-medium
                       {{ request()->routeIs('dashboard') ? 'border-b-2 border-indigo-500 text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center px-1 pt-1 text-sm font-medium
                       {{ request()->routeIs('products.*') ? 'border-b-2 border-indigo-500 text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                        Produk
                    </a>

                    <a href="{{ route('warehouses.index') }}"
                       class="inline-flex items-center px-1 pt-1 text-sm font-medium
                       {{ request()->routeIs('warehouses.*') ? 'border-b-2 border-indigo-500 text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                        Gudang
                    </a>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="relative">
                    <button class="flex items-center text-sm font-medium text-gray-700">
                        {{ Auth::user()->name }}
                    </button>

                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 rounded">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                              class="inline-flex" stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                              class="hidden" stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Menu -->
    <div x-show="open" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700">Dashboard</a>
            <a href="{{ route('products.index') }}" class="block px-4 py-2 text-gray-700">Produk</a>
            <a href="{{ route('warehouses.index') }}" class="block px-4 py-2 text-gray-700">Gudang</a>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 text-sm text-gray-600">
                {{ Auth::user()->email }}
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>
