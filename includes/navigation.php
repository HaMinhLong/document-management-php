<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/final-php" class="brand-link">
        <img src="https://upload.wikimedia.org/wikipedia/vi/a/ad/LogoTLU.jpg" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">TLU University</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="/final-php/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Admin</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="/final-php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="menu" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Thanh công cụ
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users-cog" style='font-size: 1.1rem; margin-right: 3px; margin-left: 3px'></i>
                        <p>
                            Tài khoản - phân quyền
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/final-php/user-group" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nhóm tài khoản</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/final-php/user" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tài khoản</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-building" style='font-size: 1.1rem; margin-right: 8px; margin-left: 7px'></i>
                        <p>
                            Cơ cấu tổ chức
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/final-php/department" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Khoa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/final-php/section" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bộ môn</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-database" style='font-size: 1.1rem; margin-right: 8px; margin-left: 7px'></i>
                        <p>
                            Dữ liệu
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/final-php/majors" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ngành học</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/final-php/subject" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Môn học</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/final-php/subject-type" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Loại môn học</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-file-word" style='font-size: 1.1rem; margin-right: 8px; margin-left: 7px'></i>
                        <p>
                            Đề cương
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Đề cương chi tiết</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-book" style='font-size: 1.1rem; margin-right: 6px; margin-left: 7px'></i>
                        <p>
                            Tài liệu
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tài liệu tham khảo</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Loại tài liệu tham khảo</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>