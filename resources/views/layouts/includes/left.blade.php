<div class="navbar-header">
    <ul class="nav navbar-nav flex-row">
        <li class="nav-item me-auto"><a class="navbar-brand" href=""><span class="brand-logo">
                            <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                 xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                <defs>
                                    <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%"
                                                    y2="89.4879456%">
                                        <stop stop-color="#000000" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                    <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%"
                                                    x2="37.373316%" y2="100%">
                                        <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                </defs>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                        <g id="Group" transform="translate(400.000000, 178.000000)">
                                            <path class="text-primary" id="Path"
                                                  d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z"
                                                  style="fill:currentColor"></path>
                                            <path id="Path1"
                                                  d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z"
                                                  fill="url(#linearGradient-1)" opacity="0.2"></path>
                                            <polygon id="Path-2" fill="#000000" opacity="0.049999997"
                                                     points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                                            <polygon id="Path-21" fill="#000000" opacity="0.099999994"
                                                     points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                                            <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994"
                                                     points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                        </g>
                                    </g>
                                </g>
                            </svg></span>
                <h2 class="brand-text">Biggles</h2>
            </a></li>
        <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i
                    class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                    class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                    data-ticon="disc"></i></a></li>
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
        {{-- Dashboard --}}
        @if(!is_null($role) && !is_null($permissions))
            <li class="nav-item {{ (request()->is('dashboard') || request()->route()->getName() == null) ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('dashboard.index') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboard">{{ trans('Dashboard') }}</span>
                </a>
            </li>
            {{-- Dashboard Moves --}}

        @if(in_array('move',$permissions))
                <li class="nav-item {{ (request()->is('move') || request()->is('move/*')  || request()->is('job-details/*') ||  request()->is('ancillaryservice/*')) ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('move.index') }}">
                        <i data-feather="shopping-cart"></i>
                        <span class="menu-title text-truncate" data-i18n="Move">{{ trans('Moves') }}</span>
                </a>
               </li>
            @endif

            {{-- Dashboard Depots --}}
            

            @if(in_array('depot',$permissions))
                <li class="nav-item {{ (request()->is('depot') || request()->is('depot/*') || request()->is('role/*') || request()->is('role')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('depot.index') }}">
                        <i data-feather="move"></i>
                        <span class="menu-title text-truncate" data-i18n="Depot">{{ trans('Depots') }}</span>
                    </a>
                </li>
            @endif

            {{-- Dashboard Local --}}

        @if(in_array('local',$permissions))
        <li class="nav-item {{ (request()->is('local') || request()->is('local/*')) ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{ route('local.index') }}">
                <i data-feather="map-pin"></i>
                <span class="menu-title text-truncate" data-i18n="Local">{{ trans('Local') }}</span>
            </a>
        </li>
    @endif        

            {{-- Dashboard Truck --}}
            @if(in_array('truck',$permissions))
                <li class="nav-item {{ (request()->is('truck') || request()->is('truck/*')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('truck.index') }}">
                        <i data-feather="truck"></i>
                        <span class="menu-title text-truncate" data-i18n="Truck">{{ trans('Truck') }}</span>
                    </a>
                </li>
            @endif
            

            {{-- Dashboard Interstate --}}
            
            

        @if(in_array('lane',$permissions) || in_array('trip',$permissions))
        <li class="nav-item {{ (request()->is('interstate') || request()->is('lane/*') || request()->is('trip/*') || request()->is('trip-calendar')) ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{ route('lane.inter_state') }}">
                <i data-feather="truck"></i>
                <span class="menu-title text-truncate" data-i18n="Interstate">{{ trans('Interstate') }}</span>
            </a>
        </li>
    @endif
          
            {{-- Dashboard user --}}
            @if(in_array('user',$permissions))
                <li class="nav-item {{ (request()->is('user') || request()->is('user/*') || request()->is('role/*') || request()->is('role')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('user.index') }}">
                        <i data-feather="user"></i>
                        <span class="menu-title text-truncate" data-i18n="User">{{ trans('User') }}</span>
                    </a>
                </li>
            @endif

            {{-- Dashboard Schedular --}}
          
    @if(in_array('schedule-job',$permissions))
                <li class="nav-item {{ (request()->is('schedule-job')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('schedule-job.index') }}">
                        <i data-feather="clock"></i>
                        <span class="menu-title text-truncate" data-i18n="Scheduler">{{ trans('Scheduler') }}</span>
                    </a>
                </li>
            @endif


            {{-- Dashboard Companies --}}

        
        @if(in_array('company',$permissions)  && $user->access_level == 0)
        <li class="nav-item {{ (request()->is('company')) || request()->is('company/*') ? 'active' : '' }}">
            <a class="d-flex align-items-center" href="{{ route('company.index') }}">
                <i data-feather="trending-up"></i>
                <span class="menu-title text-truncate" data-i18n="Companies">{{ trans('Companies') }}</span>
            </a>
        </li>
    @endif        

            {{-- Dashboard User Action --}}

    
            @if(in_array('company',$permissions)  && $user->access_level == 0)
            <li class="nav-item {{ (request()->is('useraction')) ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('useraction.index') }}">
                    <i data-feather="rotate-ccw"></i>
                    <span class="menu-title text-truncate" data-i18n="UserAction">{{ trans('User Action') }}</span>
                </a>
            </li>
        @endif  

            {{-- Dashboard Customer --}}


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
