<div class="main-header">

    <header class="navbar navbar-expand flex-md-row pl-4-half pr-4-half pt-3 pb-4">

        @guest
            <div class="container">


                <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
                    <li class="nav-item mr-3">
                        <b><a class="nav-link text-dark" href="/register">Register</a></b>
                    </li>
                    <li class="nav-item">
                        <b><a class="nav-link text-dark" href="/login">Login</a></b>
                    </li>
                </ul>
            </div>
        @endguest

        @auth
            @if ( ! user()->hasVerifiedEmail())
                <div class="container">
                </div>
            @endif

            @if (user()->hasVerifiedEmail())

                <button type="button" class="btn btn-light mr-3 btn-sm d-xl-none" data-toggle="modal" data-target="#sidebar-modal">
                    <i class="fal fa-bars"></i>
                </button>

                <h1 class="h4 mb-0 fc-dark-blue ml-1">@yield('heading')</h1>

                <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
                    @php $teams = user()->teams @endphp

                    @if (count($teams) == 1)
                        <li class="nav-item mr-5 px-2">
                            <span class="nav-link" id="bd-versions" aria-haspopup="true" aria-expanded="false">
                                {{-- user()->currentTeam->name --}}
                            </span>
                        </li>
                    @elseif (count($teams) > 1 && user()->currentTeam)
                        <li class="nav-item dropdown mr-4 px-2 channel-dropdown">
                            <a class="nav-link dropdown-toggle fc-dark-blue" href="#" id="bd-versions" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                {{ user()->currentTeam->name }}<i class="ml-2 fas fa-caret-down fc-gray-500"></i>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="bd-versions">
                                @foreach($teams as $team)
                                    <a class="dropdown-item px-3" href="{{ route('teams.switch', $team->id) }}">
                                        <i class="fas fa-circle mr-2 {{ user()->currentTeam->id == $team->id ? 'fc-dark-blue' : 'fc-gray-300' }}"></i>{{ $team->name }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @endif

                    <li class="nav-item dropdown pl-3 user-dropdown">

                        <a class="nav-link dropdown-toggle mr-md-1 fc-dark-blue" href="#" id="bd-versions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{{ user()->full_name }}}">
                            <img src="{{{ user()->avatar }}}" height="25" class="rounded-circle mr-2" alt="{{ user()->name }}">
                            <span class="d-none d-sm-inline-block">{{{ str_limit(user()->name, 25) }}}</span> <i class="ml-2 fas fa-caret-down fc-gray-500"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="bd-versions">
                            <a class="dropdown-item px-3" href="{{ route('profile.edit') }}"><i
                                        class="fas fa-user mr-2 fc-gray-300"></i>My Profile</a>
                            <a class="dropdown-item px-3" href=""><i class="fas fa-credit-card-front mr-2 fc-gray-300"></i>Billing</a>
                            <a class="dropdown-item px-3" href="/teams"><i
                                        class="fas fa-layer-plus mr-2 fc-gray-300"></i>My Companies</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item px-3" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                        class="fas fa-sign-out-alt mr-2 fc-gray-300"></i>Log out</a>
                        </div>
                    </li>
                </ul>
            @endif
        @endauth
    </header>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>

