<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta name="user-id" content="{{ Auth::id() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @wirechatStyles

    <!-- Template Main CSS (Should come after all libraries) -->
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"
        crossorigin="anonymous">
    @stack('multiselect-styles')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    @stack('multiselect-scripts')

    <!-- Additional Styles -->
    @stack('styles')

</head>


<body>
    <div class="min-h-screen bg-[var(--wc-light-primary)] dark:bg-[var(--wc-dark-primary)]">
            <header
                class="bg-gradient-to-r from-gray-900 to-gray-800 shadow-lg sticky top-0 z-50 backdrop-blur-sm bg-opacity-90">
                <!-- Top Bar -->
                <div class="border-b border-gray-700">
                    <div class="container mx-auto px-4">
                        <div class="flex items-center justify-between h-20">
                            <div class="flex items-center space-x-4 space-x-reverse">
                                <div class="relative group">

                                    <span
                                        class="absolute -inset-1 bg-white bg-opacity-20 rounded-full blur opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                </div>
                                <div class="flex flex-col ml-2">
                                    <span class="text-xl font-bold bg-clip-text text-transparent bg-white">
                                        نظام إدارة الموارد البشرية
                                    </span>
                                </div>
                            </div>

                            <!-- User Controls -->
                            <div class="flex items-center space-x-6 space-x-reverse">
                                <!-- Notifications -->
                                <div x-data="{ notifDropdownOpen: false }" class="relative">
                                    <button @click="notifDropdownOpen = !notifDropdownOpen"
                                        class="p-2 text-gray-300 hover:text-white rounded-full hover:bg-gray-700 transition-all duration-300 relative group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z" />
                                        </svg>
                                        @php
                                            $user = Auth::user();
                                            $unreadCount = $user ? $user->unreadNotifications()->count() : 0;
                                        @endphp
                                        @if ($unreadCount > 0)
                                            <span
                                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center transform group-hover:scale-110 transition-transform">
                                                {{ $unreadCount }}
                                            </span>
                                        @endif
                                        <span
                                            class="absolute -inset-1 rounded-full bg-white bg-opacity-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                    </button>

                                    <div x-show="notifDropdownOpen" @click.away="notifDropdownOpen=false"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute right-0 mt-2 w-96 origin-top-right z-50"
                                        style="margin-right: -20rem;" x-cloak>
                                        <div
                                            class="text-sm h-[500px] bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
                                            <x-user-notification-menu count:5 />
                                        </div>
                                    </div>
                                </div>

                                <!-- Chat dropdown -->
                                <div x-data="{ chatDropdownOpen: false }" class="relative">
                                    <button @click="chatDropdownOpen = !chatDropdownOpen"
                                        class="p-2 text-gray-300 hover:text-white rounded-full hover:bg-gray-700 transition-all duration-300 group relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 3h8a5 5 0 0 1 5 5v6a5 5 0 0 1-5 5h-4l-4 4v-4H8a5 5 0 0 1-5-5V8a5 5 0 0 1 5-5z" />
                                        </svg>
                                        <span
                                            class="absolute -inset-1 rounded-full bg-white bg-opacity-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                    </button>

                                    <div x-show="chatDropdownOpen" @click.away="chatDropdownOpen = false"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute right-0 mt-2 w-96 origin-top-right z-50"
                                        style="margin-right: -20rem;" x-cloak>
                                        <div
                                            class="text-sm h-[500px] bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
                                            <livewire:wirechat.chats />
                                        </div>
                                    </div>
                                </div>

                                <!-- User Profile with Personal Image -->
                                <div class="flex items-center space-x-4 space-x-reverse group relative">
                                    <div class="flex flex-col items-end p-3">
                                        <span class="text-sm font-medium text-white">{{ Auth::user()->name }}</span>
                                        <span class="text-xs text-gray-300">{{ __(Auth::user()->role) }}</span>
                                    </div>

                                    <div class="relative group">
                                        <img src="{{ Auth::user()->personal_image }}" alt="User Avatar"
                                            id="profileImage"
                                            class="h-12 w-12 rounded-full object-cover border-2 border-grey-400 group-hover:border-[rgba(140,4,4,0.9)] transition-all duration-300">

                                        <button type="button"
                                            class="absolute -top-2 -right-2 bg-white text-gray-800 rounded-full p-1 hover:bg-gray-200 transition-all duration-200 shadow-md"
                                            onclick="document.getElementById('profilePhotoInput').click()">
                                            <i class="bi bi-pencil-fill text-xs"></i>
                                        </button>

                                        <form id="avatarUploadForm" action="{{ route('admin.updatePhoto') }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" id="profilePhotoInput" name="personal_image"
                                                accept="image/jpeg,image/png,image/gif" style="display: none;">
                                        </form>
                                    </div>


                                    <form method="POST" action="{{ route('logout') }}" class="group">
                                        @csrf
                                        <button type="submit"
                                            class="p-2 text-gray-300 hover:text-white rounded-full hover:bg-gray-700 transition-all duration-300 relative">
                                            <i class="fas fa-sign-out-alt text-lg"></i>
                                            <span
                                                class="absolute -inset-1 rounded-full bg-white bg-opacity-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Centered Navigation with Custom Color -->
                <nav class="bg-[rgba(140,4,4,0.5)] border-t border-[rgba(140,4,4,0.7)] backdrop-blur-md">
                    <div class="container mx-auto px-4">
                        <div class="flex justify-center space-x-8 space-x-reverse">
                            {{-- @if (Auth::user()->role === 'admin') --}}
                            <a href="{{ route('dashboard') }}"
                                class="{{ request()->routeIs('dashboard') ? 'text-white border-white' : 'text-gray-200 hover:text-white border-transparent' }} py-4 px-1 inline-flex items-center text-sm font-medium border-b-2 transition-all duration-300 group relative">
                                <i class="fas fa-tachometer-alt ml-2 group-hover:text-white transition-colors"></i>
                                لوحة التحكم
                                <span
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-white scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300 {{ request()->routeIs('dashboard') ? 'scale-x-100' : '' }}"></span>
                            </a>
                            {{-- @endif --}}
                            <a href="{{ route('projects-statistics') }}"
                                class="{{ request()->routeIs('projects.*') ? 'text-white border-white' : 'text-gray-200 hover:text-white border-transparent' }} py-4 px-1 inline-flex items-center text-sm font-medium border-b-2 transition-all duration-300 group relative">
                                <i class="fas fa-project-diagram ml-2 group-hover:text-white transition-colors"></i>
                                المشاريع
                                <span
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-white scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300 {{ request()->routeIs('projects.*') ? 'scale-x-100' : '' }}"></span>
                            </a>

                            <a href="{{ route('employees.index') }}"
                                class="{{ request()->routeIs('employees.*') ? 'text-white border-white' : 'text-gray-200 hover:text-white border-transparent' }} py-4 px-1 inline-flex items-center text-sm font-medium border-b-2 transition-all duration-300 group relative">
                                <i class="fas fa-users ml-2 group-hover:text-white transition-colors"></i>
                                الموظفين
                                <span
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-white scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300 {{ request()->routeIs('employees.*') ? 'scale-x-100' : '' }}"></span>
                            </a>

                            <a href="{{ route('employee-request.index') }}"
                                class="{{ request()->routeIs('requests.*') ? 'text-white border-white' : 'text-gray-200 hover:text-white border-transparent' }} py-4 px-1 inline-flex items-center text-sm font-medium border-b-2 transition-all duration-300 group relative">
                                <i class="fas fa-clipboard-list ml-2 group-hover:text-white transition-colors"></i>
                                الطلبات
                                <span
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-white scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300 {{ request()->routeIs('requests.*') ? 'scale-x-100' : '' }}"></span>
                            </a>

                            <a href="{{ route('reports') }}"
                                class="{{ request()->routeIs('reports') ? 'text-white border-white' : 'text-gray-200 hover:text-white border-transparent' }} py-4 px-1 inline-flex items-center text-sm font-medium border-b-2 transition-all duration-300 group relative">
                                <i class="fas fa-chart-bar ml-2 group-hover:text-white transition-colors"></i>
                                التقارير
                                <span
                                    class="absolute bottom-0 left-0 w-full h-0.5 bg-white scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300 {{ request()->routeIs('reports') ? 'scale-x-100' : '' }}"></span>
                            </a>
                        </div>
                    </div>
                </nav>
            </header>
        <main class="h-[calc(100vh_-_9rem)]">
            {{ $slot }}
        </main>
    </div>

  <script>
        window.userId = {{ Auth::id() }};
    </script>
    @livewireScripts
    @wirechatAssets
    @stack('scripts')
</body>

</html>
