<!--begin::Sidebar Wrapper-->
<div class="sidebar-wrapper">
    <nav class="mt-2">
        <!--begin::Sidebar Menu-->
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview"
            role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon bi bi-box-seam-fill"></i>
                    <p>
                        Quản lý chung
                        <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @canany(['view_users', 'create_users', 'edit_users', 'delete_users', 'assign_users'])
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý tài khoản</p>
                            </a>
                        </li>
                    @endcanany
                    @canany(['view_roles', 'create_roles', 'edit_roles', 'delete_roles'])
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý vai trò</p>
                            </a>
                        </li>
                    @endcanany
                    @canany(['view_permissions', 'create_permissions', 'edit_permissions', 'delete_permissions'])
                        <li class="nav-item">
                            <a href="{{ route('admin.permissions.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý quyền</p>
                            </a>
                        </li>
                    @endcanany
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon bi bi-box-seam-fill"></i>
                    <p>
                        Quản lý khai báo
                        <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.positions.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý chức vụ</p>
                            </a>
                        </li>
                    @endcanany
                    @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.areas.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý khu vực</p>
                            </a>
                        </li>
                    @endcanany
                    @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.authors.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý tác giả</p>
                            </a>
                        </li>
                    @endcanany
                    @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.publishers.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý nhà xuất bản</p>
                            </a>
                        </li>
                    @endcanany
                    @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.categories.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý danh mục</p>
                            </a>
                        </li>
                    @endcanany
                    @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.shelves.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý kệ sách</p>
                            </a>
                        </li>
                    @endcanany
                    @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.series.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý series</p>
                            </a>
                        </li>
                    @endcanany
                    @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.posts.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý bài đăng</p>
                            </a>
                        </li>
                    @endcanany
                    @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.books.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý sách</p>
                            </a>
                        </li>
                    @endcanany
                    @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.book_copies.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý bản sao sách</p>
                            </a>
                        </li>
                    @endcanany
                </ul>
            </li>
        </ul>
        <!--end::Sidebar Menu-->
    </nav>
</div>
<!--end::Sidebar Wrapper-->
