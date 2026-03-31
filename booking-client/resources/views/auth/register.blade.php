@extends('layouts.app')

@section('title', 'Регистрация - Hotel Booking')

@section('content')
    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <!-- Логотип -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center text-2xl font-light tracking-tight">
                    <span class="text-gray-900">hotel</span>
                    <span class="text-gray-300 mx-1">·</span>
                    <span class="text-gray-900">booking</span>
                </a>
                <h2 class="mt-6 text-3xl font-light text-gray-900">
                    Создайте аккаунт
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    Присоединяйтесь к нам сегодня
                </p>
            </div>

            <!-- Форма регистрации -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                @include('partials.alerts')

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Имя -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Полное имя
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                            </div>
                            <input id="name"
                                   name="name"
                                   type="text"
                                   autocomplete="name"
                                   required
                                   autofocus
                                   value="{{ old('name') }}"
                                   class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent transition-all @error('name') border-red-300 focus:ring-red-200 @enderror"
                                   placeholder="Иван Иванов">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email адрес
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input id="email"
                                   name="email"
                                   type="email"
                                   autocomplete="email"
                                   required
                                   value="{{ old('email') }}"
                                   class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent transition-all @error('email') border-red-300 focus:ring-red-200 @enderror"
                                   placeholder="you@example.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Пароль -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Пароль
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password"
                                   name="password"
                                   type="password"
                                   autocomplete="new-password"
                                   required
                                   class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent transition-all @error('password') border-red-300 focus:ring-red-200 @enderror"
                                   placeholder="••••••••">
                            <button type="button"
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600 transition-colors"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24"
                                     x-show="!show">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600 transition-colors"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24"
                                     x-show="show"
                                     x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.025m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            Минимум 8 символов
                        </p>
                    </div>

                    <!-- Подтверждение пароля -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Подтвердите пароль
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 013 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <input id="password_confirmation"
                                   name="password_confirmation"
                                   type="password"
                                   autocomplete="new-password"
                                   required
                                   class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent transition-all"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Согласие с условиями -->
                    <div class="flex items-start">
                        <div class="flex items-center">
                            <input id="terms"
                                   name="terms"
                                   type="checkbox"
                                   required
                                   class="h-4 w-4 text-gray-900 border-gray-300 rounded focus:ring-gray-500">
                        </div>
                        <label for="terms" class="ml-3 block text-sm text-gray-600">
                            Я согласен с
                            <a href="#" class="font-medium text-gray-900 hover:text-gray-700 transition-colors underline">
                                условиями использования
                            </a>
                            и
                            <a href="#" class="font-medium text-gray-900 hover:text-gray-700 transition-colors underline">
                                политикой конфиденциальности
                            </a>
                        </label>
                    </div>
                    @error('terms')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Кнопка регистрации -->
                    <button type="submit"
                            class="w-full py-3.5 px-4 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-xl transition-all hover:scale-[1.02] shadow-lg">
                        Создать аккаунт
                    </button>
                </form>

                <!-- Разделитель -->
                <div class="mt-6 relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">или</span>
                    </div>
                </div>

                <!-- Вход -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Уже есть аккаунт?
                        <a href="{{ route('login') }}" class="font-medium text-gray-900 hover:text-gray-700 transition-colors">
                            Войти
                        </a>
                    </p>
                </div>
            </div>

            <!-- Дополнительная информация -->
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-400">
                    Защищено современными технологиями шифрования
                </p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Анимация появления формы
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.bg-white.rounded-2xl');
            if (form) {
                form.style.opacity = '0';
                form.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    form.style.transition = 'all 0.5s ease';
                    form.style.opacity = '1';
                    form.style.transform = 'translateY(0)';
                }, 100);
            }
        });
    </script>
@endpush
