<header class="border-b border-gray-100 bg-white relative z-20">
    <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center text-xl font-light tracking-tight">
                    <span class="text-gray-900">hotel</span>
                    <span class="text-gray-300 mx-1">·</span>
                    <span class="text-gray-900">booking</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}"
                   class="text-sm {{ request()->routeIs('home') ? 'text-gray-900 font-medium' : 'text-gray-400 hover:text-gray-600' }} transition-colors">
                    Главная
                </a>
                <a href="{{ route('hotels.index') }}"
                   class="text-sm {{ request()->routeIs('hotels.*') ? 'text-gray-900 font-medium' : 'text-gray-400 hover:text-gray-600' }} transition-colors">
                    Отели
                </a>
                <a href="#" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
                    Развлечения
                </a>
                <a href="#" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
                    Рестораны
                </a>
                <a href="#" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
                    Контакты
                </a>
            </nav>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none group">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 text-sm font-medium group-hover:bg-gray-200 transition-colors">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="hidden md:inline text-sm text-gray-600 group-hover:text-gray-900 transition-colors">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open"
                             @click.away="open = false"
                             x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-1 z-50 border border-gray-100">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                                Мой профиль
                            </a>
                            <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                                Мои бронирования
                            </a>
                            @if(Auth::user()->isAdmin())
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                                    Админ панель
                                </a>
                            @endif
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-50 transition-colors">
                                    Выйти
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
                        Вход
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm bg-gray-900 text-white hover:bg-gray-800 rounded-lg transition-colors">
                        Регистрация
                    </a>
                @endauth

                <!-- Mobile menu button -->
                <div class="md:hidden" x-data="{ mobileOpen: false }">
                    <button @click="mobileOpen = !mobileOpen" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div x-show="mobileOpen"
                         @click.away="mobileOpen = false"
                         x-transition
                         class="absolute left-0 right-0 top-16 bg-white border-b border-gray-100 shadow-lg z-40">
                        <nav class="container mx-auto px-4 py-4 space-y-3">
                            <a href="{{ route('home') }}" class="block py-2 text-gray-600 hover:text-gray-900 transition-colors">Главная</a>
                            <a href="{{ route('hotels.index') }}" class="block py-2 text-gray-600 hover:text-gray-900 transition-colors">Отели</a>
                            <a href="#" class="block py-2 text-gray-600 hover:text-gray-900 transition-colors">Развлечения</a>
                            <a href="#" class="block py-2 text-gray-600 hover:text-gray-900 transition-colors">Рестораны</a>
                            <a href="#" class="block py-2 text-gray-600 hover:text-gray-900 transition-colors">Контакты</a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    [x-cloak] { display: none !important; }
</style>
