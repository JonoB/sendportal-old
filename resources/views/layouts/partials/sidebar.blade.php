<div class="sidebar bg-dark-blue min-vh-100 d-none d-xl-block">

    <div class="mt-4">
        <div class="logo text-center">
            <a href="/">
                SendPortal
            </a>
        </div>
    </div>

    <div class="sidebar-inner">
        <ul class="nav flex-column mt-4" id="metismenu">

            <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="far fa-chart-bar mr-2"></i><span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('campaigns*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('campaigns.index') }}">
                    <i class="fas fa-envelope mr-2"></i><span>Campaigns</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('automations*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('automations.index') }}">
                    <i class="far fa-sync-alt mr-2"></i> <span>Automations</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('templates*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('templates.index') }}">
                    <i class="far fa-file-alt mr-2"></i> <span>Templates</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('subscribers*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('subscribers.index') }}">
                    <i class="far fa-user mr-2"></i> <span>Subscribers</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('segments*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('segments.index') }}">
                    <i class="far fa-list mr-2"></i> <span>Segments</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('messages*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('messages.index') }}">
                    <i class="far fa-paper-plane mr-2"></i> <span>Messages</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('providers*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('providers.index') }}">
                    <i class="far fa-envelope mr-2"></i> <span>Providers</span>
                </a>
            </li>
        </ul>
    </div>
</div>
