<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="{{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ Request::is('newsletters*') ? 'active' : '' }}">
                <a href="{{ route('newsletters.index') }}">
                    <i class="fa fa-dashboard"></i> <span>Newsletters</span>
                </a>
            </li>
            <li class="{{ Request::is('autoresponders*') ? 'active' : '' }}">
                <a href="{{ route('autoresponders.index') }}">
                    <i class="fa fa-dashboard"></i> <span>Autoresponders</span>
                </a>
            </li>
            <li class="{{ Request::is('templates*') ? 'active' : '' }}">
                <a href="{{ route('templates.index') }}">
                    <i class="fa fa-dashboard"></i> <span>Templates</span>
                </a>
            </li>
            <li class="{{ Request::is('contacts*') ? 'active' : '' }}">
                <a href="{{ route('contacts.index') }}">
                    <i class="fa fa-dashboard"></i> <span>Contacts</span>
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
