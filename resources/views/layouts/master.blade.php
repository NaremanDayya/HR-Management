<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | نظام إدارة الموارد البشرية</title>

    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @wirechatStyles
    <!-- Tajawal Arabic Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6e48aa;
            --primary-light: #9d50bb;
            --secondary: #02060e;
            --light: #f8f9fa;
            --dark: #000000;
        }

        body {
            white-space: nowrap;
            font-family: 'Tahoma', sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-size: 14px;
            font-weight: 500;
        }

        .swal2-popup {
            font-family: 'Tajawal', sans-serif;
            /* Arabic font */
            direction: rtl;
        }

        .swal2-title {
            font-size: 1.5rem;
        }

        .swal2-confirm {
            padding: 0.5rem 1.5rem;
        }

        /* For the spinner button */
        button .spinner-border {
            vertical-align: middle;
            margin-left: 5px;
        }

        /* Header Styles */
        .main-header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .unread-badge {
            font-size: 0.6rem;
            min-width: 1.25rem;
            height: 1.25rem;
            line-height: 1.25rem;
        }

        .brand-info {
            display: flex;
            flex-direction: column;
        }

        .system-name {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
        }

        .company-name {
            font-size: 0.85rem;
            color: var(--secondary);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-role {
            background-color: var(--primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .logout-btn {
            background: none;
            border: none;
            color: var(--secondary);
            cursor: pointer;
            font-size: 0.9rem;
        }

        .logout-btn:hover {
            color: var(--primary);
        }

        /* Navigation */
        .main-nav {
            background-color: white;
            border-top: 1px solid #eee;

        }

        .nav-container {
            display: flex;
            justify-content: center;
        }

        .nav-list {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            justify-content: center;
        }

        .text-secondary {
            color: #000 !important;
        }

        .btn-purple {
            background-color: #6e48aa;
            border: 1px solid #6e48aa;
            color: #fff;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }

        /* Hover */
        .btn-purple:hover {
            background-color: #5c3e91;
            border-color: #5c3e91;
        }

        /* Focus */
        .btn-purple:focus,
        .btn-purple:focus-visible {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(110, 72, 170, 0.5);
        }

        /* Active */
        .btn-purple:active {
            background-color: #4f3681;
            border-color: #4f3681;
        }

        /* Disabled */
        .btn-purple:disabled {
            background-color: #6e48aa;
            border-color: #6e48aa;
            opacity: 0.65;
            pointer-events: none;
        }

        .nav-item {
            padding: 1rem 1.5rem;
            position: relative;
        }

        .nav-link {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link i {
            font-size: 1.2rem;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 3px;
            background-color: var(--primary);
            border-radius: 3px 3px 0 0;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem 0;

        }

        /* Footer */
        .main-footer {
            /* background-color: var(--dark); */
            color: rgb(12, 10, 10);
            padding: 1.5rem 0;
            text-align: center;
            margin-top: auto;
        }

        .footer-text {
            margin: 0;
            font-size: 0.9rem;
        }
    </style>
    @stack('styles')

</head>

<body>
    <!-- Header Section -->
    <header
        class="bg-gradient-to-r from-gray-900 to-gray-800 shadow-lg sticky top-0 z-50 backdrop-blur-sm bg-opacity-90">
        <!-- Top Bar -->
        <div class="border-b border-gray-700">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between h-20"> <!-- Increased height -->
                    <!-- Brand Info - Wider Logo -->
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <div class="relative group">
                            <img src="{{ asset('build/assets/img/logo.png') }}" alt="Logo"
                                class="h-12 w-auto transition-all duration-300 group-hover:rotate-6 group-hover:scale-105 filter brightness-0 invert opacity-90">
                            <span
                                class="absolute -inset-1 bg-white bg-opacity-20 rounded-full blur opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        </div>
                        {{-- <div class="flex flex-col ml-2">
                            <span class="text-xl font-bold bg-clip-text text-transparent bg-white">
                                نظام إدارة الموارد البشرية
                            </span>
                        </div> --}}
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
                                class="absolute right-0 mt-2 w-96 origin-top-right z-50" style="margin-right: -20rem;"
                                x-cloak>
                                <div
                                    class="text-sm h-[500px] bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
                                    <x-user-notification-menu count:5 />
                                </div>
                            </div>
                        </div>

                        <!-- Chat dropdown -->
                        <div x-data="{
                            chatDropdownOpen: false,
                            unreadCount: 0,
                            init() {
                                window.addEventListener('chat-unread-count', (event) => {
                                    this.unreadCount = event.detail.count;
                                });
                            }
                        }" class="relative">
                            <button @click="chatDropdownOpen = !chatDropdownOpen"
                                class="p-2 text-gray-300 hover:text-white rounded-full hover:bg-gray-700 transition-all duration-300 group relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 3h8a5 5 0 0 1 5 5v6a5 5 0 0 1-5 5h-4l-4 4v-4H8a5 5 0 0 1-5-5V8a5 5 0 0 1 5-5z" />
                                </svg>


                                <!-- Unread count badge -->
                                <span x-show="unreadCount > 0" x-text="unreadCount"
                                    class="unread-badge absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full"
                                    style="font-size: 0.6rem;"></span>


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
                                class="absolute right-0 mt-2 w-96 origin-top-right z-50" style="margin-right: -20rem;"
                                x-cloak>
                                <div
                                    class="text-sm h-[500px] bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
                                    <livewire:wirechat.chats />
                                </div>
                            </div>
                        </div>

                        <!-- User Profile with Personal Image -->
                        <div class="flex items-center space-x-4 space-x-reverse group relative">
                            <div class="flex flex-col items-end">
                                <span class="text-sm font-medium text-white">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-gray-300">{{ __(Auth::user()->role) }}</span>
                            </div>

                            <div class="relative group">
                                <img src="{{ Auth::user()->personal_image }}" alt="User Avatar" id="profileImage"
                                    class="h-12 w-12 rounded-full object-cover border-2 border-grey-400 group-hover:border-[rgba(140,4,4,0.9)] transition-all duration-300">

                                <button type="button"
                                    class="absolute -top-2 -right-2 bg-white text-gray-800 rounded-full p-1 hover:bg-gray-200 transition-all duration-200 shadow-md"
                                    onclick="document.getElementById('profilePhotoInput').click()">
                                    <i class="bi bi-pencil-fill text-xs"></i>
                                </button>

                                <form id="avatarUploadForm" action="{{ route('admin.updatePhoto') }}" method="POST"
                                    enctype="multipart/form-data">
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

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">
            @if(session()->has('impersonator_id'))
                <div class="bg-yellow-200 text-yellow-800 p-2 text-center">
                    انت تستخدم الأن حساب الموظف : {{session('employee_name')}}
                    <a href="{{ url('/admin/impersonate/stop') }}" class="underline">العودة لحساب الادمن</a>
                </div>
            @endif
            @if (session('success'))
                <div class="toast-container position-fixed" style="top: 100px; right: 20px; z-index: 1300;">
                    <div class="toast show align-items-center text-bg-success border-0" role="alert"
                        aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif
            @if (session('warning'))
                <div class="toast-container position-fixed" style="top: 100px; right: 20px; z-index: 1300;">
                    <div class="toast show align-items-center text-bg-warning border-0" role="alert"
                        aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                {{ session('warning') }}
                            </div>
                            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <p class="footer-text">
                جميع الحقوق محفوظة &copy; {{ date('Y') }} نظام إدارة الموارد البشرية - شركة افاق الخليج
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('profilePhotoInput').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                const maxSize = 2048 * 1024; // 2MB
                const editBtn = document.querySelector(
                    '[onclick="document.getElementById(\'profilePhotoInput\').click()"]');

                // Validation
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'الرجاء اختيار صورة بصيغة JPEG أو PNG أو GIF',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'حجم الملف يجب أن لا يتجاوز 2MB',
                        confirmButtonText: 'حسناً'
                    });
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImage').src = e.target.result;
                }
                reader.readAsDataURL(file);

                const originalContent = editBtn.innerHTML;
                editBtn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin"></i>';
                editBtn.disabled = true;

                document.getElementById('avatarUploadForm').submit();
            }
        });
    </script>

    @livewireScripts
    @wirechatAssets
    @stack('scripts')
</body>

</html>
