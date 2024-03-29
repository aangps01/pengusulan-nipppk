<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-evenly align-items-center">
                <div class="logo">
                    <img src="{{ asset('assets/img/logo.png') }}">
                </div>
                <div class="caption ms-3 align-self-center">
                    <h4 class="sidebar-title">Pengusulan NIPPPK</h4>
                    <p class="sidebar-title-caption">Kabupaten Badung</p>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-item {{ request()->routeIs('*admin.dashboard*') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard.index') }}" class='sidebar-link'>
                        <i class="isax isax-element-4"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('*admin.permohonan*') ? 'active' : '' }}">
                    <a href="{{ route('admin.permohonan.index') }}" class='sidebar-link'>
                        <i class="isax isax-edit"></i>
                        <span>Data Permohonan</span>
                    </a>
                </li>
                {{-- <li class="sidebar-item has-sub">
                    <a href="#" class="sidebar-link">
                        <i class="isax isax-data"></i>
                        <span>Master User</span></a>
                    <ul class="submenu" style="display: block">
                        <li
                            class="submenu-item {{ request()->routeIs('admin.master-user.user-dealer*') ? 'active' : '' }}">
                            <a href="{{ route('admin.master-user.user-dealer.index') }}">
                                <i class="isax isax-user"></i>
                                <span>User Dealer</span>
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('admin.master-user.user-viewer*') ? 'active' : '' }}">
                            <a href="{{ route('admin.master-user.user-viewer.index') }}">
                                <i class="isax isax-user"></i>
                                <span>User Viewer</span>
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('admin.master-user.user-verifikator*') ? 'active' : '' }}">
                            <a href="{{ route('admin.master-user.user-verifikator.index') }}">
                                <i class="isax isax-building-4"></i>
                                <span>User Verifikator</span>
                            </a>
                        </li>
                    </ul>
                </li> --}}
                <hr>
                <li class="sidebar-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                <li><button type="submit" name="submit" class="sidebar-link dropdown-item"><i
                            class="bi-box-arrow-left"></i>
                        <span>Logout</span></button></li>
                </form>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
