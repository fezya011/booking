<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm border-b border-gray-100">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <!-- Логотип -->
            <a href="{{ route('home') }}" class="flex items-center space-x-2 hover:opacity-80 transition-opacity">
                <svg class="w-9 h-9 text-[#F53003]" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="24" cy="24" r="20" fill="currentColor" fill-opacity="0.1"/>
                    <circle cx="24" cy="24" r="18" fill="currentColor"/>
                    <path d="M16 32L32 16M32 16H20M32 16V28" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="text-xl font-light tracking-tight">
                    <span class="text-gray-900">hotel</span>
                    <span class="text-gray-400 mx-0.5">·</span>
                    <span class="text-gray-900">booking</span>
                </span>
            </a>

            <!-- Десктопная навигация -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Главная</a>
                <a href="{{ route('hotels.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Отели</a>
                <a href="#" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Развлечения</a>
                <a href="#" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Рестораны</a>
            </nav>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                @php
                    $user = Session::get('user');
                    $isLoggedIn = Session::has('api_token') && $user;
                @endphp

                @if($isLoggedIn)
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                type="button"
                                class="flex items-center space-x-2 px-3 py-2 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#F53003] to-[#FF6B4A] flex items-center justify-center text-white font-medium">
                                {{ strtoupper(substr($user['name'], 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ $user['name'] }}</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- 🔥 Меню с z-index больше хедера -->
                        <div x-show="open"
                             @click.away="open = false"
                             x-cloak
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-200">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ $user['name'] }}</p>
                                <p class="text-xs text-gray-500">{{ $user['email'] }}</p>
                            </div>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Профиль</a>
                            <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Бронирования</a>
                            @if(isset($user['role']) && $user['role'] === 'admin')
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Админ панель</a>
                            @endif
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Выйти</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Вход</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm bg-[#F53003] text-white hover:bg-[#d42a00] rounded-lg transition-colors">Регистрация</a>
                @endif

                <!-- Мобильное меню -->
                <button class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
                        x-data="{ mobileOpen: false }"
                        @click="mobileOpen = !mobileOpen"
                        type="button">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Мобильное меню -->
    <div x-show="mobileOpen"
         @click.away="mobileOpen = false"
         x-cloak
         class="md:hidden bg-white border-t border-gray-100 py-4 px-4 shadow-lg relative z-[100]">
        <nav class="flex flex-col space-y-3">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Главная</a>
            <a href="{{ route('hotels.index') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Отели</a>
            <a href="#" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Развлечения</a>
            <a href="#" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Рестораны</a>
            @if($isLoggedIn)
                <div class="border-t border-gray-100 my-2 pt-2">
                    <a href="{{ route('profile.show') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Профиль</a>
                    <a href="{{ route('bookings.index') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Бронирования</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg">Выйти</button>
                    </form>
                </div>
            @else
                <div class="border-t border-gray-100 my-2 pt-2">
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Вход</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Регистрация</a>
                </div>
            @endif
        </nav>
    </div>
</header>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
    let lastScrollTop = 0;
    const header = document.querySelector('header');
    const scrollThreshold = 100; // Через сколько пикселей скрывать

    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
            // Скролл вниз - скрываем хедер
            header.style.transform = 'translateY(-100%)';
            header.style.transition = 'transform 0.3s ease';
        } else {
            // Скролл вверх - показываем хедер
            header.style.transform = 'translateY(0)';
        }

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });
</script>
