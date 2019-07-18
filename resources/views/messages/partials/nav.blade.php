<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('messages.index') ? 'active'  : '' }}" href="{{ route('messages.index') }}">Sent</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('messages.draft') ? 'active'  : '' }}" href="{{ route('messages.draft') }}">Draft</a>
    </li>
</ul>