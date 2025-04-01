<?php

use App\Http\Controllers\Admin\Declarations\Areas\AreaController;
use App\Http\Controllers\Admin\Declarations\Authors\AuthorController;
use App\Http\Controllers\Admin\Declarations\BookCopies\BookCopyController;
use App\Http\Controllers\Admin\Declarations\Books\BookController;
use App\Http\Controllers\Admin\Declarations\Categories\CategoryController;
use App\Http\Controllers\Admin\Declarations\Positions\PositionController;
use App\Http\Controllers\Admin\Declarations\Posts\PostController;
use App\Http\Controllers\Admin\Declarations\Publishers\PublisherController;
use App\Http\Controllers\Admin\Declarations\Series\SeriesController;
use App\Http\Controllers\Admin\Declarations\Shelves\ShelfController;
use App\Http\Controllers\Admin\Permissions\PermissionController;
use App\Http\Controllers\Admin\Roles\RoleController;
use App\Http\Controllers\Admin\Users\ProfileController;
use App\Http\Controllers\Admin\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('index');
    Route::prefix('users')->name('users.')->group(function () { // Chức năng quản lý tài khoản
        Route::middleware(['canAny:view_users'])->get('/index', [UserController::class, 'index'])->name('index'); // Hiển thị danh sách tài khoản
        Route::middleware(['canAny:create_users'])->get('/create', [UserController::class, 'create'])->name('create'); // Hiển thị form tạo tài khoản
        Route::middleware(['canAny:create_users'])->post('/store', [UserController::class, 'store'])->name('store'); // Xử lý tạo tài khoản
        Route::middleware(['canAny:edit_users'])->get('/edit/{id}', [UserController::class, 'edit'])->name('edit'); // Hiển thị form chỉnh sửa
        Route::middleware(['canAny:edit_users'])->post('/update/{id}', [UserController::class, 'update'])->name('update'); // Xử lý chỉnh sửa
        Route::middleware(['canAny:delete_users'])->post('/delete/{id}', [UserController::class, 'delete'])->name('delete'); // Xử lý xóa
        // 🚀 Hiển thị giao diện phân vai trò
        Route::middleware(['canAny:assign_users'])->get('/assign-roles/{id}', [UserController::class, 'showAssignRolesForm'])->name('showAssignRolesForm');
        // 🚀 Xử lý gán vai trò cho người dùng
        Route::middleware(['canAny:assign_users'])->post('/assign-roles/{id}', [UserController::class, 'assignRoles'])->name('assignRoles');
        Route::middleware(['canAny:edit_users'])->post('/toggle-block/{id}', [UserController::class, 'changeStatus'])->name('toggleBlock');
    });

    Route::prefix('profiles')->name('profiles.')->group(function () { // Chức năng quản lý hồ sơ
        Route::middleware(['canAny:edit_users'])->get('/edit/{user_id}', [ProfileController::class, 'edit'])->name('edit'); // Hiển thị form chỉnh sửa
        Route::middleware(['canAny:edit_users'])->post('/update/{user_id}', [ProfileController::class, 'update'])->name('update'); // Xử lý chỉnh sửa
    });

    Route::prefix('roles')->name('roles.')->group(function () { // Chức năng quản lý vai trò
        Route::middleware(['canAny:view_roles'])->get('/index', [RoleController::class, 'index'])->name('index'); // Hiển thị danh sách vai trò
        Route::middleware(['canAny:create_roles'])->get('/create', [RoleController::class, 'create'])->name('create'); // Hiển thị form tạo mới vai trò
        Route::middleware(['canAny:create_roles'])->post('/store', [RoleController::class, 'store'])->name('store'); // Xử lý thêm mới vai trò
        Route::middleware(['canAny:edit_roles'])->get('/edit/{id}', [RoleController::class, 'edit'])->name('edit');
        Route::middleware(['canAny:edit_roles'])->post('/update/{id}', [RoleController::class, 'update'])->name('update');
        Route::middleware(['canAny:delete_roles'])->delete('/delete/{id}', [RoleController::class, 'delete'])->name('delete');
        Route::middleware(['canAny:view_roles'])->get('/autocomplete', [RoleController::class, 'autocomplete'])->name('autocomplete'); // Lấy vai trò theo từ
    });

    Route::prefix('permissions')->name('permissions.')->group(function () { // Chức năng quản lý quyền
        Route::middleware(['canAny:view_permissions'])->get('/index', [PermissionController::class, 'index'])->name('index'); // Hiển thị danh sách quyền
        Route::middleware(['canAny:create_permissions'])->get('/create', [PermissionController::class, 'create'])->name('create'); // Hiển thị form tạo mới quyền
        Route::middleware(['canAny:create_permissions'])->post('/store', [PermissionController::class, 'store'])->name('store'); // Xử lý thêm mới quyền
        Route::middleware(['canAny:edit_permissions'])->get('/edit/{id}', [PermissionController::class, 'edit'])->name('edit'); // Hiển thị form sửa quyền
        Route::middleware(['canAny:edit_permissions'])->post('/update/{id}', [PermissionController::class, 'update'])->name('update'); // Xử lý sửa quyền
        Route::middleware(['canAny:delete_permissions'])->delete('/delete/{id}', [PermissionController::class, 'delete'])->name('delete'); // Xử lý xóa quyền
        Route::middleware(['canAny:view_permissions'])->get('/autocomplete', [PermissionController::class, 'autocomplete'])->name('autocomplete'); // Lấy quyền theo từ
    });

    Route::prefix('declarations')->name('declarations.')->group(function () { // Chức năng quản lý khai báo
        Route::prefix('positions')->name('positions.')->group(function () { // Chức năng quản lý chức vụ
            Route::middleware(['canAny:view_declarations'])->get('/index', [PositionController::class, 'index'])->name('index'); // Hiển thị danh sách quyền
            Route::middleware(['canAny:create_declarations'])->get('/create', [PositionController::class, 'create'])->name('create'); // Hiển thị form tạo mới quyền
            Route::middleware(['canAny:create_declarations'])->post('/store', [PositionController::class, 'store'])->name('store'); // Xử lý thêm mới quyền
            Route::middleware(['canAny:edit_declarations'])->get('/edit/{id}', [PositionController::class, 'edit'])->name('edit'); // Hiển thị form sửa quyền
            Route::middleware(['canAny:edit_declarations'])->post('/update/{id}', [PositionController::class, 'update'])->name('update'); // Xử lý sửa quyền
            Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [PositionController::class, 'delete'])->name('delete'); // Xử lý xóa quyền
        });

        Route::prefix('areas')->name('areas.')->group(function () { // Chức năng quản lý khu vực
            Route::middleware(['canAny:view_declarations'])->get('/index', [AreaController::class, 'index'])->name('index'); // Hiển thị danh sách khu vực
            Route::middleware(['canAny:create_declarations'])->get('/create', [AreaController::class, 'create'])->name('create'); // Hiển thị form tạo mới khu vực
            Route::middleware(['canAny:create_declarations'])->post('/store', [AreaController::class, 'store'])->name('store'); // Xử lý thêm mới khu vực
            Route::middleware(['canAny:edit_declarations'])->get('/edit/{id}', [AreaController::class, 'edit'])->name('edit'); // Hiển thị form sửa khu vực
            Route::middleware(['canAny:edit_declarations'])->post('/update/{id}', [AreaController::class, 'update'])->name('update'); // Xử lý sửa khu vực
            Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [AreaController::class, 'delete'])->name('delete'); // Xử lý xóa khu vực
        });

        Route::prefix('authors')->name('authors.')->group(function () { // Chức năng quản lý tác giả
            Route::middleware(['canAny:view_declarations'])->get('/index', [AuthorController::class, 'index'])->name('index'); // Hiển thị danh sách tác giả
            Route::middleware(['canAny:create_declarations'])->get('/create', [AuthorController::class, 'create'])->name('create'); // Hiển thị form tạo mới tác giả
            Route::middleware(['canAny:create_declarations'])->post('/store', [AuthorController::class, 'store'])->name('store'); // Xử lý thêm mới tác giả
            Route::middleware(['canAny:edit_declarations'])->get('/edit/{id}', [AuthorController::class, 'edit'])->name('edit'); // Hiển thị form sửa tác giả
            Route::middleware(['canAny:edit_declarations'])->post('/update/{id}', [AuthorController::class, 'update'])->name('update'); // Xử lý sửa tác giả
            Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [AuthorController::class, 'delete'])->name('delete'); // Xử lý xóa tác giả
        });

        Route::prefix('categories')->name('categories.')->group(function () { // Chức năng quản lý danh mục
            Route::middleware(['canAny:view_declarations'])->get('/index', [CategoryController::class, 'index'])->name('index'); // Hiển thị danh sách danh mục
            Route::middleware(['canAny:create_declarations'])->get('/create', [CategoryController::class, 'create'])->name('create'); // Hiển thị form tạo mới danh mục
            Route::middleware(['canAny:create_declarations'])->post('/store', [CategoryController::class, 'store'])->name('store'); // Xử lý thêm mới danh mục
            Route::middleware(['canAny:edit_declarations'])->get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit'); // Hiển thị form sửa danh mục
            Route::middleware(['canAny:edit_declarations'])->post('/update/{id}', [CategoryController::class, 'update'])->name('update'); // Xử lý sửa danh mục
            Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [CategoryController::class, 'delete'])->name('delete'); // Xử lý xóa danh mục
        });

        Route::prefix('publishers')->name('publishers.')->group(function () { // Chức năng quản lý nhà xuất bản
            Route::middleware(['canAny:view_declarations'])->get('/index', [PublisherController::class, 'index'])->name('index'); // Hiển thị danh sách nhà xuất bản
            Route::middleware(['canAny:create_declarations'])->get('/create', [PublisherController::class, 'create'])->name('create'); // Hiển thị form tạo mới nhà xuất bản
            Route::middleware(['canAny:create_declarations'])->post('/store', [PublisherController::class, 'store'])->name('store'); // Xử lý thêm mới nhà xuất bản
            Route::middleware(['canAny:edit_declarations'])->get('/edit/{id}', [PublisherController::class, 'edit'])->name('edit'); // Hiển thị form sửa nhà xuất bản
            Route::middleware(['canAny:edit_declarations'])->post('/update/{id}', [PublisherController::class, 'update'])->name('update'); // Xử lý sửa nhà xuất bản
            Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [PublisherController::class, 'delete'])->name('delete'); // Xử lý xóa nhà xuất bản
            Route::middleware(['canAny:edit_declarations'])->get('/autocomplete', [PublisherController::class, 'autocomplete'])->name('autocomplete'); // Lấy nhà xuất bản theo từ
        });

        Route::prefix('shelves')->name('shelves.')->group(function () { // Chức năng quản lý kệ sách
            Route::middleware(['canAny:view_declarations'])->get('/index', [ShelfController::class, 'index'])->name('index'); // Hiển thị danh sách kệ sách
            Route::middleware(['canAny:create_declarations'])->get('/create', [ShelfController::class, 'create'])->name('create'); // Hiển thị form tạo mới kệ sách
            Route::middleware(['canAny:create_declarations'])->post('/store', [ShelfController::class, 'store'])->name('store'); // Xử lý thêm mới kệ sách
            Route::middleware(['canAny:edit_declarations'])->get('/edit/{id}', [ShelfController::class, 'edit'])->name('edit'); // Hiển thị form sửa kệ sách
            Route::middleware(['canAny:edit_declarations'])->post('/update/{id}', [ShelfController::class, 'update'])->name('update'); // Xử lý sửa kệ sách
            Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [ShelfController::class, 'delete'])->name('delete'); // Xử lý xóa kệ sách
        });

        Route::prefix('series')->name('series.')->group(function () { // Chức năng quản lý series
            Route::middleware(['canAny:view_declarations'])->get('/index', [SeriesController::class, 'index'])->name('index'); // Hiển thị danh sách series
            Route::middleware(['canAny:create_declarations'])->get('/create', [SeriesController::class, 'create'])->name('create'); // Hiển thị form tạo mới series
            Route::middleware(['canAny:create_declarations'])->post('/store', [SeriesController::class, 'store'])->name('store'); // Xử lý thêm mới series
            Route::middleware(['canAny:edit_declarations'])->get('/edit/{id}', [SeriesController::class, 'edit'])->name('edit'); // Hiển thị form sửa series
            Route::middleware(['canAny:edit_declarations'])->post('/update/{id}', [SeriesController::class, 'update'])->name('update'); // Xử lý sửa series
            Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [SeriesController::class, 'delete'])->name('delete'); // Xử lý xóa series
            Route::middleware(['canAny:edit_declarations'])->get('/autocomplete', [SeriesController::class, 'autocomplete'])->name('autocomplete'); // Lấy series theo từ
        });

        Route::prefix('posts')->name('posts.')->group(function () { // Chức năng quản lý bài đăng
            Route::middleware(['canAny:view_declarations'])->get('/index', [PostController::class, 'index'])->name('index'); // Hiển thị danh sách bài đăng
            Route::middleware(['canAny:create_declarations'])->get('/create', [PostController::class, 'create'])->name('create'); // Hiển thị form tạo mới bài đăng
            Route::middleware(['canAny:create_declarations'])->post('/store', [PostController::class, 'store'])->name('store'); // Xử lý thêm mới bài đăng
            Route::middleware(['canAny:edit_declarations'])->get('/edit/{id}', [PostController::class, 'edit'])->name('edit'); // Hiển thị form sửa bài đăng
            Route::middleware(['canAny:edit_declarations'])->post('/update/{id}', [PostController::class, 'update'])->name('update'); // Xử lý sửa bài đăng
            Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [PostController::class, 'delete'])->name('delete'); // Xử lý xóa bài đăng
        });

        Route::prefix('books')->name('books.')->group(function () { // Chức năng quản lý sách
            Route::middleware(['canAny:view_declarations'])->get('/index', [BookController::class, 'index'])->name('index'); // Hiển thị danh sách sách
            Route::middleware(['canAny:create_declarations'])->get('/create', [BookController::class, 'create'])->name('create'); // Hiển thị form tạo mới sách
            Route::middleware(['canAny:create_declarations'])->post('/store', [BookController::class, 'store'])->name('store'); // Xử lý thêm mới sách
            Route::middleware(['canAny:edit_declarations'])->get('/edit/{id}', [BookController::class, 'edit'])->name('edit'); // Hiển thị form sửa sách
            Route::middleware(['canAny:edit_declarations'])->post('/update/{id}', [BookController::class, 'update'])->name('update'); // Xử lý sửa sách
            Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [BookController::class, 'delete'])->name('delete'); // Xử lý xóa sách
            Route::middleware(['canAny:edit_declarations'])->get('/autocomplete', [BookController::class, 'autocomplete'])->name('autocomplete'); // Lấy sách theo từ
        });

        Route::prefix('book_copies')->name('book_copies.')->group(function () { // Chức năng quản lý bản sao sách
            Route::middleware(['canAny:view_declarations'])->get('/index', [BookCopyController::class, 'index'])->name('index'); // Hiển thị danh sách bản sao sách
            Route::middleware(['canAny:create_declarations'])->get('/create', [BookCopyController::class, 'create'])->name('create'); // Hiển thị form tạo mới bản sao sách
            Route::middleware(['canAny:create_declarations'])->post('/store', [BookCopyController::class, 'store'])->name('store'); // Xử lý thêm mới bản sao sách
            Route::middleware(['canAny:edit_declarations'])->get('/edit/{id}', [BookCopyController::class, 'edit'])->name('edit'); // Hiển thị form sửa bản sao sách
            Route::middleware(['canAny:edit_declarations'])->post('/update/{id}', [BookCopyController::class, 'update'])->name('update'); // Xử lý sửa bản sao sách
            Route::middleware(['canAny:delete_declarations'])->delete('/delete/{id}', [BookCopyController::class, 'delete'])->name('delete'); // Xử lý xóa bản sao sách
        });

    });
});
