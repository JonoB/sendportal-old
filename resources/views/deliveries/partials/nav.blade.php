<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('deliveries.index') ? 'active'  : '' }}" href="{{ route('deliveries.index') }}">Sent</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('deliveries.draft') ? 'active'  : '' }}" href="{{ route('deliveries.draft') }}">Draft</a>
    </li>
</ul>