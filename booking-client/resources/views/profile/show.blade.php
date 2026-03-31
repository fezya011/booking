@extends('layouts.app')

@section('title', 'Мой профиль - Hotel Booking')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
            <!-- Заголовок -->
            <div class="mb-8">
                <h1 class="text-3xl font-light text-gray-900 mb-2">Мой профиль</h1>
                <p class="text-gray-500">Управляйте личной информацией и настройками</p>
            </div>

            @include('partials.alerts')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Левая колонка - Карточка профиля -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <!-- Аватар -->
                        <div class="text-center mb-6">
                            <div class="relative inline-block">
                                @if(isset($user['avatar']) && $user['avatar'])
                                    <img src="{{ $user['avatar'] }}" 
                                         alt="{{ $user['name'] }}"
                                         class="w-32 h-32 rounded-full object-cover ring-4 ring-gray-100">
                                @else
                                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-white text-4xl font-medium ring-4 ring-gray-100 shadow-lg">
                                        {{ strtoupper(substr($user['name'], 0, 1)) }}
                                    </div>
                                @endif
                                <!-- Бейдж верификации -->
                                <span class="absolute bottom-1 right-1 w-8 h-8 bg-green-500 border-4 border-white rounded-full"></span>
                            </div>
                            <h2 class="mt-4 text-xl font-semibold text-gray-900">{{ $user['name'] }}</h2>
                            <p class="text-sm text-gray-500">{{ $user['email'] }}</p>
                            @if(isset($user['role']) && $user['role'] === 'admin')
                                <span class="inline-block mt-2 px-3 py-1 bg-gray-900 text-white text-xs font-medium rounded-full">
                                    Администратор
                                </span>
                            @endif
                        </div>

                        <!-- Кнопки действий -->
                        <div class="space-y-3">
                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center justify-center w-full px-4 py-2.5 bg-gray-900 text-white hover:bg-gray-800 rounded-xl font-medium transition-all hover:scale-[1.02]">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Редактировать профиль
                            </a>
                            <a href="{{ route('bookings.index') }}" 
                               class="flex items-center justify-center w-full px-4 py-2.5 bg-gray-50 text-gray-700 hover:bg-gray-100 rounded-xl font-medium transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Мои бронирования
                            </a>
                        </div>
                    </div>

                    <!-- Статистика -->
                    @if(isset($stats))
                        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Статистика</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <span class="ml-3 text-sm text-gray-600">Бронирований</span>
                                    </div>
                                    <span class="text-lg font-semibold text-gray-900">{{ $stats['bookings_count'] ?? 0 }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <span class="ml-3 text-sm text-gray-600">Завершено</span>
                                    </div>
                                    <span class="text-lg font-semibold text-gray-900">{{ $stats['completed_bookings'] ?? 0 }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                            </svg>
                                        </div>
                                        <span class="ml-3 text-sm text-gray-600">Отзывов</span>
                                    </div>
                                    <span class="text-lg font-semibold text-gray-900">{{ $stats['reviews_count'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Правая колонка - Информация -->
                <div class="lg:col-span-2">
                    <!-- Личная информация -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Личная информация</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Имя</label>
                                <p class="text-gray-900">{{ $user['name'] }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                                <p class="text-gray-900">{{ $user['email'] }}</p>
                            </div>
                            @if(isset($user['phone']) && $user['phone'])
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Телефон</label>
                                    <p class="text-gray-900">{{ $user['phone'] }}</p>
                                </div>
                            @endif
                            @if(isset($user['created_at']))
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Дата регистрации</label>
                                    <p class="text-gray-900">{{ date('d.m.Y', strtotime($user['created_at'])) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Смена пароля -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Безопасность</h3>
                        
                        <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Текущий пароль
                                </label>
                                <input type="password"
                                       id="current_password"
                                       name="current_password"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent transition-all @error('current_password') border-red-300 focus:ring-red-200 @enderror"
                                       placeholder="••••••••">
                                @error('current_password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Новый пароль
                                    </label>
                                    <input type="password"
                                           id="password"
                                           name="password"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent transition-all @error('password') border-red-300 focus:ring-red-200 @enderror"
                                           placeholder="••••••••">
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Подтверждение
                                    </label>
                                    <input type="password"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent transition-all"
                                           placeholder="••••••••">
                                </div>
                            </div>

                            <button type="submit" 
                                    class="px-6 py-3 bg-gray-900 text-white hover:bg-gray-800 rounded-xl font-medium transition-all hover:scale-[1.02]">
                                Изменить пароль
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
