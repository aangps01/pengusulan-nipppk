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
                <li class="sidebar-item {{ request()->routeIs('*user.pengajuan*') ? 'active' : '' }}">
                    <a href="{{ route('user.pengajuan.index') }}" class='sidebar-link'>
                        <i class="isax isax-element-4"></i>
                        <span>Pengusulan</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->routeIs('*user.dokumen-tambahan*') ? 'active' : '' }}">
                    <a href="{{ route('user.dokumen-tambahan.index') }}" class='sidebar-link'>
                        <i class="isax isax-element-4"></i>
                        <span>Berkas Wajib Tambahan</span>
                    </a>
                </li>
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
