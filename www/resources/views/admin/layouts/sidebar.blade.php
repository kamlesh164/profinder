@php
$setting = App\Models\Setting::first();
@endphp

<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">{{ $setting->sidebar_lg_header }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}">{{ $setting->sidebar_sm_header }}</a>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>

            <li class="nav-item dropdown {{ Route::is('admin.designations.*') || Route::is('admin.states.*') || Route::is('admin.cities.*') || Route::is('admin.sliders.*') || Route::is('admin.coupan.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-shopping-cart"></i><span>Masters</span></a>

                <ul class="dropdown-menu">
                    {{-- <li class="{{ Route::is('admin.apartment.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.apartment.index') }}">Apartments</a></li>
                    <li class="{{ Route::is('admin.designations.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.designations.index') }}">Designations</a></li> --}}

                    <li class="{{ Route::is('admin.states.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.states.index') }}">States</a></li>

                    <li class="{{ Route::is('admin.cities.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.cities.index') }}">Cities</a></li>
                    <li class="{{ Route::is('admin.sliders.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.sliders.index') }}">Sliders</a></li>
                    <li class="{{ Route::is('admin.coupan.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.coupan.index') }}">Coupon/Cashback</a></li>

                </ul>
            </li>
            <li class="nav-item dropdown {{ Route::is('admin.product.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-list"></i> <span>Products</span>

                </a>
                <ul class="dropdown-menu">
                    <li class="{{  Route::is('admin.product.index') || Route::is('admin.product.details') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.product.index')}}">Products List</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown {{ Route::is('admin.order.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-shopping-cart"></i><span>All Orders</span></a>

                <ul class="dropdown-menu">
                    <li class="{{ Route::is('admin.order.index') || Route::is('admin.order.show') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.order.index')}}">All Orders</a></li>

                    <li class="{{ Route::is('admin.order.pending') || Route::is('admin.order.show') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.order.pending')}}">Pending Orders</a></li>

                    <li class="{{ Route::is('admin.order.complete') || Route::is('admin.order.show') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.order.complete')}}">Complete Orders</a></li>

                </ul>
            </li>
            <li class="nav-item dropdown {{ Route::is('admin.category.*') || Route::is('admin.servicecategory.*') ? 'active' : '' }} ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-th-large"></i><span>Categories</span></a>

                <ul class="dropdown-menu">
                    {{-- <li class="{{ Route::is('admin.category.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.category.index') }}">Product Categories</a></li> --}}
                    <li class="{{ Route::is('admin.servicecategory.*') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.servicecategory.index')}}">Service Categories</a></li>
                </ul>
            </li>
            {{-- <li class="{{ Route::is('admin.service.*') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.service.index')}}"><i class="fas fa-th-large"></i> <span>Services</span></a></li> --}}

            <li class="nav-item dropdown {{ Route::is('admin.vendors.*') || Route::is('admin.vendor.kyc.pending') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span>Old Vendor Management</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Route::is('admin.vendors.*') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.vendors.index')}}">Vendor Lists</a></li>
                    <li class="{{ Route::is('admin.vendor.kyc.pending') || Route::is('admin.vendor.kyc.details') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.vendor.kyc.pending')}}">Pending KYC</a></li>
                    {{-- <li class=""><a class="nav-link" href="">Approved KYC</a></li> --}}
                </ul>
            </li>
            <li class="nav-item dropdown {{ Route::is('admin.subscriptions.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-list"></i> <span>Subscription</span>
                </a><ul class="dropdown-menu">
                    <li class="{{  Route::is('admin.subscriptions.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{route('admin.subscriptions.index')}}">
                            Subscription Plan
                        </a>
                    </li>
                    <li class="{{ Route::is('admin.subscriptions.purchase-history') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.subscriptions.purchase-history') }}">
                            Purchase History
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown {{ Route::is('admin.jobpost.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-briefcase"></i> <span>Job Posts</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{  Route::is('admin.jobpost.*') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.jobpost.index')}}">Job Posts</a></li> 
                </ul>
            </li>
            <li class="{{ Route::is('admin.service-professional.*') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.service-professional.index')}}"><i class="fas fa-users"></i> <span>New Vendor Registration</span></a></li>
            <li class="{{ Route::is('admin.customers.*') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.customers.index')}}"><i class="fas fa-users"></i> <span>User Management</span></a></li>
            {{-- <li class="nav-item dropdown {{  Route::is('admin.blogs.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-globe"></i><span>Manage Website</span></a>
                <ul class="dropdown-menu">
                    <li class="{{  Route::is('admin.blogs.*') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.blogs.index')}}">Blogs</a></li>
                    <li class=""><a class="nav-link" href="">SEO Setup</a></li>
                </ul>
            </li> --}}
            {{-- <li class="{{ Route::is('admin.notification.*') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.notification.index')}}"><i class="fas fa-bell"></i> <span>Push Notification</span></a></li> --}}

            {{-- <li class="nav-item dropdown {{ Route::is('admin.vendor.report.*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-cog"></i><span>Reports</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Route::is('admin.vendor.report.*') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.vendor.report.index')}}">Vendor Report</a></li>
                    <li class=""><a class="nav-link" href="">Orders</a></li>
                </ul>
            </li> --}}
            <li class="{{ Route::is('admin.setting') ? 'active' : '' }}"><a class="nav-link" href="{{route('admin.setting')}}"><i class="fas fa-cog"></i> <span>Setting</span></a></li>

        </ul>

    </aside>
</div>
