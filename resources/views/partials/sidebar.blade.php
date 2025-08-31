<aside id="sidebar" class="sidebar shadow-sm bg-white" dir="rtl">
    <style>
        .sidebar {
            --primary-color: #2563eb;
            --hover-color: #1e40af;
            --active-bg: rgba(37, 99, 235, 0.1);
            --group-color: #94a3b8;
            --transition: 0.3s ease;
            width: 260px;
            padding: 1.5rem 1rem;
            font-family: 'Tajawal', 'Inter', sans-serif;
            border-right: 1px solid #e5e7eb;
            height: 100vh;
            direction: rtl;
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 0.5rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        .sidebar-header .logo {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            color: #6b7280;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin: 0.35rem 0;
            border-radius: 12px;
            color: #1f2937;
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            transition: background-color var(--transition), color var(--transition);
        }

        .sidebar .nav-link i,
        .sidebar .nav-link .nav-icon {
            font-size: 1.2rem;
            margin-left: 1rem;
            margin-right: 0;
            color: var(--primary-color);
            transition: color var(--transition);
        }

        .sidebar .nav-link:hover {
            background-color: var(--active-bg);
            color: var(--hover-color);
        }

        .sidebar .nav-link:hover i,
        .sidebar .nav-link:hover .nav-icon {
            color: var(--hover-color);
        }

        .sidebar .nav-link.active {
            background-color: var(--active-bg);
            color: var(--hover-color);
            font-weight: 600;
        }

        .sidebar .nav-link.active i,
        .sidebar .nav-link.active .nav-icon {
            color: var(--hover-color);
        }

        .nav-group {
            margin-top: 1.5rem;
        }

        .nav-group-header {
            color: var(--group-color);
            text-transform: uppercase;
            font-size: 0.75rem;
            margin: 0.5rem 0.75rem 0.75rem 0;
            font-weight: 600;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .nav-group-header i {
            font-size: 1rem;
            margin-left: 0.5rem;
            margin-right: 0;
            color: var(--primary-color);
        }

        .nav-group-items {
            padding-right: 0;
            list-style: none;
        }

        .nav-group-items .nav-link {
            padding-right: 2.75rem;
            padding-left: 1rem;
            font-size: 0.9rem;
        }

        .nav-group-items .nav-item:before {
            right: 2rem;
            left: auto;
            content: "";
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            background-color: var(--primary-color);
            border-radius: 50%;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            right: 0;
            left: 0;
            padding: 1.5rem 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .user-profile {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            margin-left: 0.75rem;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            display: block;
            font-weight: 600;
            color: #1f2937;
        }

        .user-role {
            display: block;
            font-size: 0.75rem;
            color: #6b7280;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.75rem;
            background-color: #f3f4f6;
            color: #ef4444;
            border-radius: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #e5e7eb;
        }

        .logout-btn svg {
            margin-left: 0.5rem;
        }
    </style>
    @php
    $salesRepId = null; // Initialize as null
    if (Auth::user()->role == 'salesRep' && Auth::user()->salesRep) {
    $salesRepId = Auth::user()->salesRep->id;
    }
    @endphp
    <ul class="sidebar-nav" id="sidebar-nav">
        @if(Auth::user()->hasRole('admin'))
        <li class="nav-item">
            <a href="{{ route('sales-reps.index') }}"
                class="nav-link {{ request()->routeIs('sales-reps.index') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <span class="nav-text">المندوبين</span>
            </a>
        </li>
        @elseif(Auth::user()->role == ('salesRep'))
        <li class="nav-item">
            <a href="{{ route('sales-rep.targets.index',$salesRepId) }}"
                class="nav-link {{ request()->routeIs('sales-rep.targets.index',$salesRepId) ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </span>
                <span class="nav-text">التارجت</span>
            </a>
        </li>
        @endif

        <li class="nav-item">
            <a @if(Auth::user()->hasRole('admin')) href="{{ route('allClients') }}"
                @elseif(Auth::user()->role('salesRep')) href="{{
                route('sales-reps.clients.index',$salesRepId) }}"
                @endif
                class="nav-link {{ request()->routeIs('allClients') ||
                request()->routeIs('sales-reps.clients.index',$salesRepId) ?
                'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </span>
                <span class="nav-text">العملاء</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('services.index') }}"
                class="nav-link {{ request()->routeIs('services.index') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <span class="nav-text">الخدمات</span>
            </a>
        </li>
        @if(Auth::user()->hasRole('admin'))
        <li class="nav-item">
            <a href="{{ route('salesreps.credentials') }}"
                class="nav-link {{ request()->routeIs('salesreps.credentials') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
                <span class="nav-text">بيانات الدخول للمندوبين</span>
            </a>
        </li>
        @endif
        <li class="nav-item">
            <a @if(Auth::user()->hasRole('admin')) href="{{ route('allAgreements') }}"
                @elseif(Auth::user()->role('salesRep')) href="{{
                route('salesrep.agreements.index',$salesRepId) }}"
                @endif
                class="nav-link {{ request()->routeIs('allAgreements') ||
                request()->routeIs('salesrep.agreements.index',$salesRepId) ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </span>
                <span class="nav-text">الاتفاقيات</span>
            </a>
        </li>
        <li>
            <a @if(Auth::user()->hasRole('admin')) href="{{ route('admin.allPendingRequests') }}"
                @elseif(Auth::user()->role('salesRep')) href="{{ route('myPendingRequests',$salesRepId)
                }}"
                @endif
                class="nav-link {{ request()->routeIs('admin.allPendingRequests') ||
                request()->routeIs('myPendingRequests',$salesRepId) ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <span class="nav-text">الطلبات المعلقة</span>
            </a>

        </li>
    </ul>

    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-avatar">
                <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random' }}"
                    alt="User Avatar">
            </div>
            <div class="user-info">
                <span class="user-name">{{ Auth::user()->name }}</span>
                <span class="user-role">{{ Auth::user()->role }}</span>
            </div>
        </div>
        <a href="{{ route('logout') }}" class="logout-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16 17L21 12L16 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <span>تسجيل الخروج</span>
        </a>
    </div>
</aside>

