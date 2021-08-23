<div class="navbar-header">
    <ul class="nav navbar-nav flex-row">
        <li class="nav-item me-auto">
            <a class="navbar-brand" href="">
                <span class="brand-logo"></span>
                <h2 class="brand-text">Biggles</h2>
            </a>
        </li>
        <li class="nav-item nav-toggle">
            <a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse">
                <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                <i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                   data-ticon="disc"></i>
            </a>
        </li>
    </ul>
</div>
<div class="shadow-bottom"></div>
<div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
    @php
        $user = \Illuminate\Support\Facades\Auth::user();
        $role = $user->role;
        $permissions = null;
        if(!is_null($role)){
            $permissions = $role->permissions()->pluck('route_name')->toArray();
        }
    @endphp

    @if(!is_null($role) && !is_null($permissions))
        <!-- Dashboard -->
            <li class="nav-item {{ (request()->is('dashboard') || request()->route()->getName() == null) ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('dashboard.index') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboard">{{ trans('Dashboard') }}</span>
                </a>
            </li>
            <!-- Move Menu-->
            @if(in_array('move',$permissions))
                <li class="nav-item {{ (request()->is('move') || request()->is('move/*')  || request()->is('job-details/*')
                    ||  request()->is('ancillaryservice/*')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('move.index') }}">
                        <i data-feather="shopping-cart"></i>
                        <span class="menu-title text-truncate" data-i18n="Move">{{ trans('Moves') }}</span>
                    </a>
                </li>
            @endif
        <!-- Depot Menu-->
            @if(in_array('depot',$permissions))
                <li class="nav-item {{ (request()->is('depot') || request()->is('depot/*') || request()->is('role/*') ||
                    request()->is('role')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('depot.index') }}">
                        <i data-feather="move"></i>
                        <span class="menu-title text-truncate" data-i18n="Depot">{{ trans('Depots') }}</span>
                    </a>
                </li>
            @endif
        <!-- Local Menu-->
            @if(in_array('local',$permissions))
                <li class="nav-item {{ (request()->is('local') || request()->is('local/*')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('local.index') }}">
                        <i data-feather="map-pin"></i>
                        <span class="menu-title text-truncate" data-i18n="Local">{{ trans('Local') }}</span>
                    </a>
                </li>
            @endif
        <!-- Truck Menu-->
            @if(in_array('truck',$permissions))
                <li class="nav-item {{ (request()->is('truck') || request()->is('truck/*')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('truck.index') }}">
                        <i data-feather="truck"></i>
                        <span class="menu-title text-truncate" data-i18n="Truck">{{ trans('Truck') }}</span>
                    </a>
                </li>
            @endif
        <!-- Lane & Trip Menu -->
            @if(in_array('lane',$permissions) || in_array('trip',$permissions))
                <li class="nav-item {{ (request()->is('interstate') || request()->is('lane/*') || request()->is('trip/*')
                    || request()->is('trip-calendar')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('lane.inter_state') }}">
                        <i data-feather="truck"></i>
                        <span class="menu-title text-truncate" data-i18n="Interstate">{{ trans('Interstate') }}</span>
                    </a>
                </li>
            @endif
            {{--<!-- User Menu -->
                @if(in_array('user',$permissions))
                    <li class="nav-item {{ (request()->is('user') || request()->is('user/*') || request()->is('role/*') ||
                        request()->is('role')) ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('user.index') }}">
                            <i data-feather="user"></i>
                            <span class="menu-title text-truncate" data-i18n="User">{{ trans('User') }}</span>
                        </a>
                    </li>
                @endif
            <!-- Schedule Job Menu -->
                @if(in_array('schedule-job',$permissions))
                    <li class="nav-item {{ (request()->is('schedule-job')) ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('schedule-job.index') }}">
                            <i data-feather="clock"></i>
                            <span class="menu-title text-truncate" data-i18n="Scheduler">{{ trans('Scheduler') }}</span>
                        </a>
                    </li>
                @endif
            <!-- Company Menu -->
                @if(in_array('company',$permissions)  && $user->access_level == 0)
                    <li class="nav-item {{ (request()->is('company')) || request()->is('company/*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('company.index') }}">
                            <i data-feather="trending-up"></i>
                            <span class="menu-title text-truncate" data-i18n="Companies">{{ trans('Companies') }}</span>
                        </a>
                    </li>
                @endif--}}
        <!-- User Action Menu -->
            @if(in_array('useraction',$permissions)  && $user->access_level == 0)
                <li class="nav-item {{ (request()->is('useraction')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('useraction.index') }}">
                        <i data-feather="rotate-ccw"></i>
                        <span class="menu-title text-truncate" data-i18n="UserAction">{{ trans('User Action') }}</span>
                    </a>
                </li>
            @endif
        <!-- Customer Menu -->
            @if(in_array('customer',$permissions)  && $user->access_level == 0)
                <li class="nav-item {{ (request()->is('customer') || request()->is('customer/*')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('customer.index') }}">
                        <i data-feather="user"></i>
                        <span class="menu-title text-truncate" data-i18n="Customer">{{ trans('Customer') }}</span>
                    </a>
                </li>
            @endif
        @endif
    </ul>
</div>
