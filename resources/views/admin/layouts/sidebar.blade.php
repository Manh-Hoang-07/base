<div class="left-side-menu left-side-menu-detached">
    <!--- Sidemenu -->
    <ul class="metismenu side-nav">
        <li class="side-nav-title side-nav-item bg-primary-lighten text-white"><h6>Quản lý chung</h6></li>
        @canany(['view_users', 'create_users', 'edit_users', 'delete_users', 'assign_users'])
            <li class="side-nav-item">
                <a href="{{ route('admin.users.index') }}" class="side-nav-link">
                    <i class="dripicons-chevron-right"></i>
                    <span>Quản lý tài khoản</span>
                </a>
            </li>
        @endcan
        @canany(['view_roles', 'create_roles', 'edit_roles', 'delete_roles'])
            <li class="side-nav-item">
                <a href="{{ route('admin.roles.index') }}" class="side-nav-link">
                    <i class="dripicons-chevron-right"></i>
                    <span>Quản lý vai trò</span>
                </a>
            </li>
        @endcanany
        @canany(['view_permissions', 'create_permissions', 'edit_permissions', 'delete_permissions'])
            <li class="side-nav-item">
                <a href="{{ route('admin.permissions.index') }}" class="side-nav-link">
                    <i class="dripicons-chevron-right"></i>
                    <span>Quản lý quyền</span>
                </a>
            </li>
        @endcanany
        <li class="side-nav-title side-nav-item bg-primary-lighten text-white"><h6>Quản lý khai báo</h6></li>
        @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
        <li class="side-nav-item">
            <a href="{{ route('admin.declarations.positions.index') }}" class="side-nav-link">
                <i class="dripicons-chevron-right"></i>
                <span>Quản lý chức vụ</span>
            </a>
        </li>
        @endcanany
        @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
            <li class="side-nav-item">
                <a href="{{ route('admin.declarations.areas.index') }}" class="side-nav-link">
                    <i class="dripicons-chevron-right"></i>
                    <span>Quản lý khu vực</span>
                </a>
            </li>
        @endcanany
        @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
            <li class="side-nav-item">
                <a href="{{ route('admin.declarations.authors.index') }}" class="side-nav-link">
                    <i class="dripicons-chevron-right"></i>
                    <span>Quản lý tác giả</span>
                </a>
            </li>
        @endcanany
        @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
            <li class="side-nav-item">
                <a href="{{ route('admin.declarations.publishers.index') }}" class="side-nav-link">
                    <i class="dripicons-chevron-right"></i>
                    <span>Quản lý nhà xuất bản</span>
                </a>
            </li>
        @endcanany
        @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
            <li class="side-nav-item">
                <a href="{{ route('admin.declarations.categories.index') }}" class="side-nav-link">
                    <i class="dripicons-chevron-right"></i>
                    <span>Quản lý danh mục</span>
                </a>
            </li>
        @endcanany
        @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
            <li class="side-nav-item">
                <a href="{{ route('admin.declarations.shelves.index') }}" class="side-nav-link">
                    <i class="dripicons-chevron-right"></i>
                    <span>Quản lý kệ sách</span>
                </a>
            </li>
        @endcanany
    </ul>
    <!-- End Sidebar -->

    <div class="clearfix"></div>
    <!-- Sidebar -left -->
</div>
