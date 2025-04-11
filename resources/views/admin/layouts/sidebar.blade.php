<!--begin::Sidebar Wrapper-->
<div class="sidebar-wrapper">
    <nav class="mt-2">
        <!--begin::Sidebar Menu-->
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

            {{-- Quản lý chung --}}
            @php
                $activeMenuGroup1 = isActive(['admin.users.*', 'admin.roles.*', 'admin.permissions.*'], 'menu-open');
                $activeLinkGroup1 = isActive(['admin.users.*', 'admin.roles.*', 'admin.permissions.*']);
            @endphp

            <li class="nav-item {{ $activeMenuGroup1 }}">
                <a href="#" class="nav-link {{ $activeLinkGroup1 }}">
                    <i class="nav-icon bi bi-box-seam-fill"></i>
                    <p>
                        Quản lý chung
                        <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @canany(['view_users', 'create_users', 'edit_users', 'delete_users', 'assign_users'])
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ isActive('admin.users.*') }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý tài khoản</p>
                            </a>
                        </li>
                    @endcanany

                    @canany(['view_roles', 'create_roles', 'edit_roles', 'delete_roles'])
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}" class="nav-link {{ isActive('admin.roles.*') }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý vai trò</p>
                            </a>
                        </li>
                    @endcanany

                    @canany(['view_permissions', 'create_permissions', 'edit_permissions', 'delete_permissions'])
                        <li class="nav-item">
                            <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ isActive('admin.permissions.*') }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý quyền</p>
                            </a>
                        </li>
                    @endcanany
                </ul>
            </li>


            {{-- Quản lý khai báo --}}
            <li class="nav-item {{ isActive('admin.declarations.*', 'menu-open') }}">
                <a href="#" class="nav-link {{ isActive('admin.declarations.*') }}">
                    <i class="nav-icon bi bi-box-seam-fill"></i>
                    <p>
                        Quản lý khai báo
                        <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @canany(['view_declarations', 'create_declarations', 'edit_declarations', 'delete_declarations'])
                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.positions.index') }}" class="nav-link {{ isActive('admin.declarations.positions.*') }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý chức vụ</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.areas.index') }}" class="nav-link {{ isActive('admin.declarations.areas.*') }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý khu vực</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.authors.index') }}" class="nav-link {{ isActive('admin.declarations.authors.*') }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý tác giả</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.publishers.index') }}" class="nav-link {{ isActive('admin.declarations.publishers.*') }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý nhà xuất bản</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.categories.index') }}" class="nav-link {{ isActive('admin.declarations.categories.*') }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý danh mục</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.shelves.index') }}" class="nav-link {{ isActive('admin.declarations.shelves.*') }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý kệ sách</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.series.index') }}" class="nav-link {{ isActive('admin.declarations.series.*') }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý series</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.posts.index') }}" class="nav-link {{ isActive('admin.declarations.posts.*') }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý bài đăng</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.books.index') }}" class="nav-link {{ isActive('admin.declarations.books.*') }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Quản lý sách</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.declarations.book_copies.index') }}" class="nav-link {{ isActive('admin.declarations.book_copies.*') }}">
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
