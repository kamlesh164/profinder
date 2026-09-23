@php
$setting = App\Models\Setting::first();
@endphp

@include('admin.layouts.head')

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <div class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
                    </ul>
                </div>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown dropdown-list-toggle"><a target="_blank" href="{{url('/')}}" class="nav-link nav-link-lg"><i class="fas fa-home mr-2"></i>Visit Website</i></a>
                    </li>
                    @php
                    $header_admin=Auth::guard('admin')->user();
                    $setting = App\Models\Setting::first();
                    $default_avatar = array(
                    'image' => $setting->default_avatar
                    );
                    $default_avatar = (object) $default_avatar;

                    @endphp
                    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            @if ($header_admin->image)
                            <img alt="image" src="{{ asset($header_admin->image) }}" class="rounded-circle mr-1">
                            @else
                            <img alt="image" src="{{ asset($default_avatar->image) }}" class="rounded-circle mr-1">
                            @endif
                            <div class="d-sm-none d-lg-inline-block">{{ $header_admin->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{route('admin.profile')}}" class="dropdown-item has-icon">
                                <i class="far fa-user"></i>Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('admin.signout') }}" class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            @include('admin.layouts.sidebar')

            @yield('admin-content')

            <footer class="main-footer">
                <div class="footer-left">
                   All Right reserved
                </div>

            </footer>
        </div>
    </div>

    @include('admin.layouts.foot')
