<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Clients -->
        <li class="nav-item">
            <div class="nav-group">
                <div class="nav-group-header">
                    <i class="bi bi-people"></i>
                    <span>My Clients</span>
                </div>
                <ul class="nav-group-items">

                    <li class="nav-item">
                        <a href="{{ route('sales-reps.clients.index',Auth::id()) }}" class="nav-link {{ request()->routeIs('sales-reps.clients.index') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span>Client List</span>
                        </a>
                    </li>
                       <li class="nav-item">
                        <a href="{{ route('sales-reps.clients.create', Auth::id()) }}" class="nav-link {{ request()->routeIs('sales-reps.clients.create') ? 'active' : '' }}">
                            <i class="bi bi-person-plus"></i>
                            <span>Add Client</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Performance -->
        <li class="nav-item">
            <div class="nav-group">
                <div class="nav-group-header">
                    <i class="bi bi-graph-up"></i>
                    <span>Performance</span>
                </div>
                <ul class="nav-group-items">
                    <li class="nav-item">
                        <a href="{{ route('sales-rep.targets.index',Auth::id()) }}" class="nav-link {{ request()->routeIs('targets.index') ? 'active' : '' }}">
                            <i class="bi bi-bullseye"></i>
                            <span>Targets</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.index') ? 'active' : '' }}">
                            <i class="bi bi-collection"></i>
                            <span>Services</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Client Requests -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('client-request.index') ? 'active' : '' }}" href="{{ route('client-request.index') }}">
                <i class="bi bi-pencil-square"></i>
                <span>Edit Requests</span>
            </a>
        </li>

        <!-- Messaging -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('chat.index') ? 'active' : '' }}" href="#">
                <i class="bi bi-chat-dots"></i>
                <span>Messaging</span>
            </a>
        </li>

        <!-- Profile -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li>

        <!-- Notifications -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                <i class="bi bi-bell"></i>
                <span>Notifications</span>
                @auth
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge bg-danger rounded-pill ms-auto">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
                @endauth
            </a>
        </li>

    </ul>
</aside>
