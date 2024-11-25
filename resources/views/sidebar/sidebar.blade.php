<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                
                <li class="menu-title">
                    <span>Main</span>
                </li>
                <!-- Dashboard Section -->
                <li class="{{ set_active(['home', 'em/dashboard']) }}">
                    <a href="{{ route('home') }}" class="{{ set_active(['home', 'em/dashboard']) ? 'noti-dot' : '' }}">
                        <i class="la la-dashboard"></i>
                        <span> Dashboard</span>
                    </a>
                </li>
                
                @if (Auth::user()->role_name == 'Admin')
                    <!-- topic 
                    <li class="menu-title">
                        <span>Authentication</span>
                    </li>
                    -->

                    <!-- User Controller Section -->
                    <li class="{{ set_active(['search/user/list', 'userManagement', 'activity/log', 'activity/login/logout']) }}">
                        <a href="{{ route('userManagement') }}" class="{{ set_active(['search/user/list', 'userManagement', 'activity/log', 'activity/login/logout']) ? 'noti-dot' : '' }}">
                            <i class="la la-user-secret"></i> <span> Employees</span>
                        </a>
                    </li>
                @endif
                <!-- topic 
                <li class="menu-title">
                    <span>Employees</span>
                </li>
                -->

                <!-- Leaves Section -->
                <li class="{{ set_active(['form/leaves/employee/new', 'form/leavesettings/page']) }}">
                    <a href="{{ route('form/leaves/employee/new') }}" class="{{ set_active(['form/leaves/employee/new']) ? 'noti-dot' : '' }}">
                        <i class="la la-user"></i> <span> Leaves</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
