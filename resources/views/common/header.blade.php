<header class="main-header">

    <div class="container">
        <a href="{{ route('dashboard') }}" class="logo">
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">SendPortal</span>
        </a>
        <nav class="navbar">


            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                            <img src="{{ Auth::user()->avatar_url }}" class="user-image" alt="{{ Auth::user()->name }}">
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="{{ Auth::user()->avatar_url }}" class="img-circle" alt="{{ Auth::user()->name }}">
                                <p>
                                    {{ Auth::user()->name }}
                                    <small>
                                        Registered on {{ Auth::user()->created_at->format('d-m-Y') }}
                                    </small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="btn btn-default btn-flat">Sign out</button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
