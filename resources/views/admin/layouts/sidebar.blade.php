<div class="left-side-menu left-side-menu-detached">
    <!--- Sidemenu -->
    <ul class="metismenu side-nav">
        <li class="side-nav-title side-nav-item bg-primary-lighten text-white"><h6>For Products</h6></li>
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
        <li class="side-nav-item">
            <a href="{{ route('admin.declarations.positions.index') }}" class="side-nav-link">
                <i class="dripicons-chevron-right"></i>
                <span>Quản lý chức vụ</span>
            </a>
        </li>
    </ul>
    <!-- End Sidebar -->

    <div class="clearfix"></div>
    <!-- Sidebar -left -->
</div>
