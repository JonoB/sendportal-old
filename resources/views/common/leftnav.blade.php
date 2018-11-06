<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="{{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-bar-chart"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ Request::is('campaigns*') ? 'active' : '' }}">
                <a href="{{ route('campaigns.index') }}">
                    <i class="fa fa-send"></i> <span>Campaigns</span>
                </a>
            </li>
            <li class="{{ Request::is('autoresponders*') ? 'active' : '' }}">
                <a href="{{ route('autoresponders.index') }}">
                    <i class="fa fa-refresh"></i> <span>Autoresponders</span>
                </a>
            </li>
            <li class="{{ Request::is('templates*') ? 'active' : '' }}">
                <a href="{{ route('templates.index') }}">
                    <i class="fa fa-file-text"></i> <span>Templates</span>
                </a>
            </li>
            <li class="{{ Request::is('lists*') ? 'active' : '' }}">
                <a href="{{ route('lists.index') }}">
                    <i class="fa fa-user"></i> <span>Subscribers</span>
                </a>
            </li>
            <li>
                <a href="">
                    <i class="fa fa-dashboard"></i> <span>Drafts</span>
                </a>
            </li>
            <li>
                <a href="">
                    <i class="fa fa-dashboard"></i> <span>Delivery logs</span>
                </a>
            </li>
            <li>
                <a href="">
                    <i class="fa fa-dashboard"></i> <span>Activity</span>
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
