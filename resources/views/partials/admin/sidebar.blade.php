<aside id="sidebar" class="sidebar shadow-sm bg-white">
    <style>
        .sidebar {
            --primary-color: #2563eb;
            --hover-color: #1e40af;
            --active-bg: rgba(37, 99, 235, 0.1);
            --group-color: #94a3b8;
            --transition: 0.3s ease;
            width: 260px;
            padding: 1.5rem 1rem;
            font-family: 'Inter', sans-serif;
            border-right: 1px solid #e5e7eb;
            height: 100vh;
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

        .sidebar .nav-link i {
            font-size: 1.2rem;
            margin-right: 1rem;
            color: var(--primary-color);
            transition: color var(--transition);
        }

        .sidebar .nav-link:hover {
            background-color: var(--active-bg);
            color: var(--hover-color);
        }

        .sidebar .nav-link:hover i {
            color: var(--hover-color);
        }

        .sidebar .nav-link.active {
            background-color: var(--active-bg);
            color: var(--hover-color);
            font-weight: 600;
        }

        .sidebar .nav-link.active i {
            color: var(--hover-color);
        }

        .nav-group {
            margin-top: 1.5rem;
        }

        .nav-group-header {
            color: var(--group-color);
            text-transform: uppercase;
            font-size: 0.75rem;
            margin: 0.5rem 0 0.75rem 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
        }

        .nav-group-header i {
            font-size: 1rem;
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .nav-group-items {
            padding-left: 0;
            list-style: none;
        }

        .nav-group-items .nav-link {
            padding-left: 2.75rem;
            font-size: 0.9rem;
        }

        .nav-group-items .nav-link:before {
            content: "";
            position: absolute;
            left: 2rem;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            background-color: var(--primary-color);
            border-radius: 50%;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            background-color: #ef4444;
            color: white;
            border-radius: 9999px;
            margin-left: auto;
        }
    </style>

    <ul class="sidebar-nav" id="sidebar-nav">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Sales Team -->
        <li class="nav-item nav-group">
            <div class="nav-group-header">
                <i class="bi bi-people-fill"></i> Sales Team
            </div>
            <ul class="nav-group-items">
                <li>
                    <a href="{{ route('sales-reps.index') }}"
                        class="nav-link {{ request()->routeIs('sales-reps.index') ? 'active' : '' }}">
                        <i class="bi bi-list-ul"></i> All Representatives
                    </a>
                </li>
                <li>
                    <a href="{{ route('sales-reps.create') }}"
                        class="nav-link {{ request()->routeIs('sales-reps.create') ? 'active' : '' }}">
                        <i class="bi bi-person-plus"></i> Add New
                    </a>
                </li>
            </ul>
        </li>

        <!-- Client Requests -->
        <li class="nav-item nav-group">
            <div class="nav-group-header">
                <i class="bi bi-clipboard-check"></i> Client Requests
            </div>
            <ul class="nav-group-items">
                <li>
                    <a href="{{ route('admin.client-edit-requests.index') }}"
                        class="nav-link {{ request()->routeIs('admin.client-edit-requests.*') ? 'active' : '' }}">
                        <i class="bi bi-list-check"></i> All Requests
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.client-edit-requests.pended') }}"
                        class="nav-link {{ request()->routeIs('admin.client-edit-requests.pended') ? 'active' : '' }}">
                        <i class="bi bi-hourglass-split"></i> Pending Requests
                    </a>
                </li>
            </ul>
        </li>

        <!-- Messaging -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('chat.index') ? 'active' : '' }}" href="#">
                <i class="bi bi-chat-dots"></i> Messaging
            </a>
        </li>

        <!-- Performance -->
        <li class="nav-item nav-group">
            <div class="nav-group-header">
                <i class="bi bi-graph-up"></i> Performance
            </div>
            <ul class="nav-group-items">
                <li>
                    <a href="{{ route('sales-rep.targets.index',Auth::id()) }}"
                        class="nav-link {{ request()->routeIs('targets.index') ? 'active' : '' }}">
                        <i class="bi bi-bullseye"></i> Targets
                    </a>
                </li>
                <li>
                    <a href="{{ route('services.index') }}"
                        class="nav-link {{ request()->routeIs('services.index') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i> Services
                    </a>
                </li>
            </ul>
        </li>

        <!-- Profile -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}"
                href="{{ route('profile.show') }}">
                <i class="bi bi-person"></i> Profile
            </a>
        </li>

        <!-- Notifications -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}"
                href="{{ route('notifications.index') }}">
                <i class="bi bi-bell"></i> Notifications
                @auth
                @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="badge">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
                @endif
                @endauth
            </a>
        </li>
    </ul>
</aside>
