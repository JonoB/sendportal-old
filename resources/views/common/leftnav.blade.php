<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-bar-chart"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->is('campaigns*') ? 'active' : '' }}">
                <a href="{{ route('campaigns.index') }}">
                    <i class="fa fa-send"></i> <span>Campaigns</span>
                </a>
            </li>
            <li class="{{ request()->is('automations*') ? 'active' : '' }}">
                <a href="{{ route('automations.index') }}">
                    <i class="fa fa-refresh"></i> <span>Automation</span>
                </a>
            </li>
            <li class="{{ request()->is('templates*') ? 'active' : '' }}">
                <a href="{{ route('templates.index') }}">
                    <i class="fa fa-file-text"></i> <span>Templates</span>
                </a>
            </li>
            <li class="{{ request()->is('subscribers*') ? 'active' : '' }}">
                <a href="{{ route('subscribers.index') }}">
                    <i class="fa fa-users"></i> <span>Subscribers</span>
                </a>
            </li>
            <li class="{{ request()->is('segments*') ? 'active' : '' }}">
                <a href="{{ route('segments.index') }}">
                    <i class="fa fa-list"></i> <span>Segments</span>
                </a>
            </li>
            <li class="{{ request()->is('providers*') ? 'active' : '' }}">
                <a href="{{ route('providers.index') }}">
                    <i class="fa fa-envelope"></i> <span>Providers</span>
                </a>
            </li>
        </ul>
    </section>
</aside>
