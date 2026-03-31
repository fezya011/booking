@extends('layouts.app')

@section('title', 'Редактирование профиля - Hotel Booking')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4 lg:px-8 max-w-2xl">
            <div class="mb-8">
                <a href="{{ route('profile.show') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Назад к профилю
                </a>
                <h1 class="text-3xl font-light text-gray-900 mb-2">Редактирование профиля</h1>
                <p class="text-gray-500">Измените вашу личную информацию</p>
            </div>

            @include('partials.alerts')

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Аватар -->
                    <div class="pb-6 border-b border-gray-100">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Фото профиля
                        </label>
                        <div class="flex items-center space-x-6">
                            <!-- Превью аватара -->
                            <div class="relative">
                                @if(isset($user['avatar']) && $user['avatar'])
                                    <img src="{{ $user['avatar'] }}"
                                         alt="Аватар"
                                         id="avatar-preview"
                                         class="w-20 h-20 rounded-full object-cover ring-4 ring-gray-100">
                                @else
                                    <div id="avatar-placeholder"
                                         class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-white text-2xl font-medium ring-4 ring-gray-100">
                                        {{ strtoupper(substr($user['name'], 0, 1)) }}
                                    </div>
                                    <img id="avatar-preview"
                                         class="hidden w-20 h-20 rounded-full object-cover ring-4 ring-gray-100">
                                @endif
                            </div>

                            <!-- Кнопка выбора фото -->
                            <div>
                                <label for="avatar"
                                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all cursor-pointer">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Выбрать фото
                                </label>
                                <input type="file"
                                       id="avatar"
                                       name="avatar"
                                       accept="image/jpeg,image/png,image/jpg,image/gif"
                                       class="hidden"
                                       onchange="previewAvatar(event)">
                                <p class="mt-2 text-xs text-gray-400">
                                    JPG, PNG, GIF • до 2MB
                                </p>
                            </div>
                        </div>
                        @error('avatar')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Основная информация -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Имя <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $user['name'] ?? '') }}"
                               required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent @error('name') border-red-300 @enderror"
                               placeholder="Иван Иванов">
                        @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $user['email'] ?? '') }}"
                               required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent @error('email') border-red-300 @enderror"
                               placeholder="you@example.com">
                        @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Телефон
                        </label>
                        <input type="tel"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $user['phone'] ?? '') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-transparent @error('phone') border-red-300 @enderror"
                               placeholder="+7 999 123-45-67">
                        @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Кнопки -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                        <a href="{{ route('profile.show') }}"
                           class="px-6 py-3 text-gray-700 hover:text-gray-900 font-medium transition-colors">
                            Отмена
                        </a>
                        <button type="submit"
                                class="px-8 py-3 bg-gray-900 text-white hover:bg-gray-800 rounded-xl font-medium transition-all hover:scale-[1.02] shadow-lg">
                            Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewAvatar(event) {
            const file = event.target.files[0];

            if (!file) return;

            // Проверяем тип файла
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Пожалуйста, выберите файл изображения (JPG, PNG, GIF)');
                event.target.value = '';
                return;
            }

            // Проверяем размер (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Файл слишком большой. Максимальный размер 2MB');
                event.target.value = '';
                return;
            }

            // Показываем превью
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                const placeholder = document.getElementById('avatar-placeholder');

                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }
            }
            reader.readAsDataURL(file);
        }
    </script>
@endpush
