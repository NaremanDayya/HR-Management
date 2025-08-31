<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@php
$salesRepId = null;
if (Auth::user()->role == 'salesRep' && Auth::user()->salesRep) {
$salesRepId = Auth::user()->salesRep->id;
}
@endphp
<header dir="rtl" class="sticky top-0 z-40">
    <div class="header-container flex items-center justify-between h-24 px-6 py-4 bg-white dark:bg-neutral-900 shadow-sm border-b border-gray-200 dark:border-neutral-700 w-full">
        <!-- Logo - Larger and more prominent -->
        <div class="header-brand flex-shrink-0">
            <a href="{{ route('dashboard') }}" class="logo block transition-transform hover:scale-105">
                <div class="flex items-center space-x-2 space-x-reverse">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="logo-img h-16 w-auto">
                </div>
            </a>
        </div>

        <!-- Main Navigation - Centered and improved -->
        <nav class="nav flex-row items-center gap-1 mx-4 hidden lg:flex">
            @if(Auth::user()->hasRole('admin'))
            <a href="{{ route('sales-reps.index') }}"
               class="nav-link px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors duration-200 {{ request()->routeIs('sales-reps.index') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-neutral-800' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </span>
                <span class="nav-text">المندوبين</span>
            </a>
            @elseif(Auth::user()->role == 'salesRep')
            <a href="{{ route('sales-rep.targets.index', $salesRepId) }}"
               class="nav-link px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors duration-200 {{ request()->routeIs('sales-rep.targets.index', Auth::id()) ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-neutral-800' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </span>
                <span class="nav-text">التارجت</span>
            </a>
            @endif

            <a @if(Auth::user()->hasRole('admin'))
               href="{{ route('allClients') }}"
               @elseif(Auth::user()->role == 'salesRep')
               href="{{ route('sales-reps.clients.index', $salesRepId) }}"
               @endif
               class="nav-link px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors duration-200 {{ request()->routeIs('allClients') || request()->routeIs('sales-reps.clients.index', $salesRepId) ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-neutral-800' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </span>
                <span class="nav-text">العملاء</span>
            </a>

            <a href="{{ route('services.index') }}"
               class="nav-link px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors duration-200 {{ request()->routeIs('services.index') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-neutral-800' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <span class="nav-text">الخدمات</span>
            </a>

            <a @if(Auth::user()->hasRole('admin'))
               href="{{ route('allAgreements') }}"
               @elseif(Auth::user()->role == 'salesRep')
               href="{{ route('salesrep.agreements.index', $salesRepId) }}"
               @endif
               class="nav-link px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors duration-200 {{ request()->routeIs('allAgreements') || request()->routeIs('salesrep.agreements.index', $salesRepId) ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-neutral-800' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </span>
                <span class="nav-text">الاتفاقيات</span>
            </a>

            <a @if(Auth::user()->hasRole('admin'))
               href="{{ route('admin.allRequests') }}"
               @elseif(Auth::user()->role == 'salesRep')
               href="{{ route('myRequests', $salesRepId) }}"
               @endif
               class="nav-link px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-colors duration-200 {{ request()->routeIs('admin.allRequests') || request()->routeIs('myRequests', $salesRepId) ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-neutral-800' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <span class="nav-text">الطلبات </span>            </a>
        </nav>

        <div class="header-actions flex items-center gap-3">
            <div x-data="{ clientChatDropdownOpen: false }" class="relative" dir="rtl">
                @include('partials.chatClient')
            </div>

<div x-data="{ chatDropdownOpen: false }" class="relative">
    <!-- Chat toggle button -->
    <button
        @click="chatDropdownOpen = !chatDropdownOpen"
        class="p-2 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors duration-200 relative"
    >
        <!-- Chat icon SVG -->
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M8 3h8a5 5 0 0 1 5 5v6a5 5 0 0 1-5 5h-4l-4 4v-4H8a5 5 0 0 1-5-5V8a5 5 0 0 1 5-5z"
            />
        </svg>
    </button>

    <!-- Dropdown panel -->
    <div
        x-show="chatDropdownOpen"
        @click.away="chatDropdownOpen = false"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        class="absolute right-0 mt-2 w-96 origin-top-right"
        style="margin-right: -20rem;"
        x-cloak
    >
        <div class="text-sm h-[500px] z-50 bg-white border overflow-hidden dark:border-neutral-700 rounded-xl shadow-xl border-neutral-200/70 text-neutral-700 dark:bg-neutral-900">
            <livewire:wirechat.chats />
        </div>
    </div>
</div>

            <!-- Notifications Dropdown -->
            @php
                $user = Auth::user();
                $unreadCount = $user ? $user->unreadNotifications()->count() : 0;
            @endphp
            <div x-data="{ notifDropdownOpen: false }" class="relative">
                <button @click="notifDropdownOpen = !notifDropdownOpen"
                        class="p-2 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 transition-colors duration-200 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center transform translate-x-1/2 -translate-y-1/2 animate-pulse">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>
                <div x-show="notifDropdownOpen" @click.away="notifDropdownOpen=false"
                     x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
                     class="absolute right-0 mt-2 w-96 origin-top-right z-50" style="margin-right: -20rem;" x-cloak>
                    <div class="text-sm h-[500px] bg-white border overflow-hidden dark:border-neutral-700 rounded-xl shadow-xl border-neutral-200/70 text-neutral-700 dark:bg-neutral-900">
                        <x-user-notification-menu count:5 />
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div x-data="{ dropdownOpen: false }" class="relative">
                <button @click="dropdownOpen = !dropdownOpen" type="button"
                        class="relative flex items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 p-1 shadow hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 group">
                    <span class="sr-only">Open user menu</span>
                    <div class="relative h-10 w-10 rounded-full overflow-hidden border-2 border-white/30 group-hover:border-white/50 transition-all duration-300">
                        <img class="h-full w-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="Profile photo">
                        <div class="absolute inset-0 bg-white/10 group-hover:bg-white/20 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-400 ring-2 ring-white"></span>
                </button>
                <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                     x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-neutral-800 dark:border dark:border-neutral-700 z-50"
                     style="margin-right: -12rem;" x-cloak>
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-neutral-700">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-500 truncate dark:text-gray-300">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('profile.show.custom') }}"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-neutral-700 transition-colors duration-200">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>الملف الشخصي</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-neutral-700 transition-colors duration-200">
                                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>تسجيل الخروج</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<style>
    .header-container {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .logo-img {
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }

    .nav-link {
        transition: all 0.2s ease-in-out;
    }

    .nav-link:hover {
        transform: translateY(-1px);
    }

    .active-nav-link {
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06);
    }
    .nav-text{
       font-size:14px;
       font-weight:800;
    }

</style>
