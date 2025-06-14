<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Tạo permissions cha
        $userManagement = Permission::firstOrCreate(['name' => 'user_management'], [
            'title' => 'Quản lý người dùng',
            'guard_name' => 'web',
            'status' => 1,
            'is_default' => false
        ]);

        $contentManagement = Permission::firstOrCreate(['name' => 'content_management'], [
            'title' => 'Quản lý nội dung',
            'guard_name' => 'web',
            'status' => 1,
            'is_default' => false
        ]);

        $systemManagement = Permission::firstOrCreate(['name' => 'system_management'], [
            'title' => 'Quản lý hệ thống',
            'guard_name' => 'web',
            'status' => 1,
            'is_default' => false
        ]);

        // Tạo permissions con cho User Management
        Permission::firstOrCreate(['name' => 'view_users'], [
            'title' => 'Xem danh sách người dùng',
            'parent_id' => $userManagement->id,
            'guard_name' => 'web',
            'status' => 1,
            'is_default' => true
        ]);

        Permission::firstOrCreate(['name' => 'create_users'], [
            'title' => 'Tạo người dùng mới',
            'parent_id' => $userManagement->id,
            'guard_name' => 'web',
            'status' => 1,
            'is_default' => false
        ]);

        Permission::firstOrCreate(['name' => 'edit_users'], [
            'title' => 'Chỉnh sửa người dùng',
            'parent_id' => $userManagement->id,
            'guard_name' => 'web',
            'status' => 1,
            'is_default' => false
        ]);

        Permission::firstOrCreate(['name' => 'delete_users'], [
            'title' => 'Xóa người dùng',
            'parent_id' => $userManagement->id,
            'guard_name' => 'web',
            'status' => 1,
            'is_default' => false
        ]);

        // Tạo permissions con cho Content Management
        Permission::firstOrCreate(['name' => 'view_posts'], [
            'title' => 'Xem bài viết',
            'parent_id' => $contentManagement->id,
            'guard_name' => 'web',
            'status' => 1,
            'is_default' => true
        ]);

        Permission::firstOrCreate(['name' => 'create_posts'], [
            'title' => 'Tạo bài viết',
            'parent_id' => $contentManagement->id,
            'guard_name' => 'web',
            'status' => 1,
            'is_default' => false
        ]);

        Permission::firstOrCreate(['name' => 'edit_posts'], [
            'title' => 'Chỉnh sửa bài viết',
            'parent_id' => $contentManagement->id,
            'guard_name' => 'web',
            'status' => 1,
            'is_default' => false
        ]);

        Permission::firstOrCreate(['name' => 'delete_posts'], [
            'title' => 'Xóa bài viết',
            'parent_id' => $contentManagement->id,
            'guard_name' => 'web',
            'status' => 1,
            'is_default' => false
        ]);

        // Tạo permissions con cho System Management
        Permission::firstOrCreate(['name' => 'manage_roles'], [
            'title' => 'Quản lý vai trò',
            'parent_id' => $systemManagement->id,
            'guard_name' => 'web',
            'status' => 1,
            'is_default' => false
        ]);

        Permission::firstOrCreate(['name' => 'manage_permissions'], [
            'title' => 'Quản lý quyền hạn',
            'parent_id' => $systemManagement->id,
            'guard_name' => 'web',
            'status' => 1,
            'is_default' => false
        ]);

        Permission::firstOrCreate(['name' => 'system_settings'], [
            'title' => 'Cài đặt hệ thống',
            'parent_id' => $systemManagement->id,
            'guard_name' => 'web',
            'status' => 1,
            'is_default' => false
        ]);

        echo "Đã tạo các quyền thành công!\n";
    }
}
