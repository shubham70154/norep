<div class="sidebar">
    <nav class="sidebar-nav ps ps--active-y">

        <ul class="nav">
            <li class="nav-item">
                <a href="{{ route("admin.home") }}" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt">

                    </i>
                    {{ trans('global.dashboard') }}
                </a>
            </li>
            <!-- <li class="nav-item nav-dropdown">
                <a class="nav-link  nav-dropdown-toggle">
                    <i class="fas fa-users nav-icon">

                    </i>
                    {{ trans('global.userManagement.title') }}
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                            <i class="fas fa-unlock-alt nav-icon">

                            </i>
                            {{ trans('global.permission.title') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                            <i class="fas fa-briefcase nav-icon">

                            </i>
                            {{ trans('global.role.title') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                            <i class="fas fa-user nav-icon">

                            </i>
                            {{ trans('global.user.title') }}
                        </a>
                    </li>
                </ul>
            </li> -->
            <li class="nav-item nav-dropdown">
                <a class="nav-link  nav-dropdown-toggle">
                    <i class="fas fa-users nav-icon">

                    </i>
                    {{ trans('global.userManagement.title') }}
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                            <i class="fas fa-user nav-icon">

                            </i>
                            Event Organizer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.refereelist') }}" class="nav-link {{ request()->is('admin/users/refereelist') || request()->is('admin/users/*') ? 'active' : '' }}">
                            <i class="fas fa-user nav-icon">

                            </i>
                            Referee List
                        </a>
                    </li>
                </ul>
            </li>
            <!-- <li class="nav-item">
                <a href="{{ route("admin.events.index") }}" class="nav-link {{ request()->is('admin/events') || request()->is('admin/events/*') ? 'active' : '' }}">
                    <i class="fas fa-cogs nav-icon">

                    </i>
                    {{ trans('global.eventManagement.title') }}
                </a>
            </li> -->

            <li class="nav-item nav-dropdown">
                <a class="nav-link  nav-dropdown-toggle">
                    <i class="fa fa-calendar nav-icon">

                    </i>
                    {{ trans('global.eventManagement.title') }}
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                    <a href="{{ route("admin.events.index") }}" class="nav-link {{ request()->is('admin/events') || request()->is('admin/events/*') ? 'active' : '' }}">
                            <i class="fa fa-calendar nav-icon">

                            </i>
                            All Events
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.events.runningeventlist') }}" class="nav-link {{ request()->is('admin/events/runningeventlist') || request()->is('admin/events/*') ? 'active' : '' }}">
                            <i class="fa fa-calendar nav-icon">

                            </i>
                            Running Events
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.events.upcomingeventlist') }}" class="nav-link {{ request()->is('admin/events/upcomingeventlist') || request()->is('admin/events/*') ? 'active' : '' }}">
                            <i class="fa fa-calendar nav-icon">

                            </i>
                            Upcoming Events
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.events.pasteventlist') }}" class="nav-link {{ request()->is('admin/events/pasteventlist') || request()->is('admin/events/*') ? 'active' : '' }}">
                            <i class="fa fa-calendar nav-icon">

                            </i>
                            Past Events
                        </a>
                    </li>
                </ul>
            </li>

            
            <li class="nav-item">
                <a href="{{ route("admin.referees.index") }}" class="nav-link {{ request()->is('admin/referees') || request()->is('admin/referees/*') ? 'active' : '' }}">
                    <i class="fas fa-cogs nav-icon">

                    </i>
                    {{ trans('global.refereeManagement.title') }}
                </a>
            </li>

            <li class="nav-item">
            <a href="{{ route("admin.bookedevents.list") }}" class="nav-link {{ request()->is('admin/bookedevents') || request()->is('admin/bookedevents/*') ? 'active' : '' }}">
                    <i class="fas fa-cogs nav-icon">

                    </i>
                    {{ trans('global.bookedEvent.title') }}
                </a>
            </li>

            <li class="nav-item">
            <a href="{{ route("admin.manualnotifications.index") }}" class="nav-link {{ request()->is('admin/manualnotifications') || request()->is('admin/manualnotifications/*') ? 'active' : '' }}">
                    <i class="fas fa-cogs nav-icon">

                    </i>
                    {{ trans('global.manualNotifications.title') }}
                </a>
            </li>

            <li class="nav-item">
            <a href="{{ route("admin.helpsupports.list") }}" class="nav-link {{ request()->is('admin/helpsupports') || request()->is('admin/helpsupports/*') ? 'active' : '' }}">
                    <i class="fas fa-cogs nav-icon">

                    </i>
                    {{ trans('global.supportSection.title') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.walletmanagement.list', 7) }}" class="nav-link {{ request()->is('admin/walletmanagement') || request()->is('admin/walletmanagement/*') ? 'active' : '' }}">
                    <i class="fas fa-cogs nav-icon">

                    </i>
                    {{ trans('global.walletManagement.title') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="nav-icon fas fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
        </ul>

        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 0px; height: 869px; right: 0px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 415px;"></div>
        </div>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>