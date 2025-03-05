<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = ['name', 'guard_name', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }

    // Lấy tất cả quyền cha (đệ quy)
    public function getAllParents()
    {
        $parents = collect();
        $parent = $this->load('parent')->parent; // Tải trước quan hệ parent
        $visitedIds = [];

        while ($parent) {
            if (in_array($parent->id, $visitedIds)) {
                break;
            }

            $parents->push($parent);
            $visitedIds[] = $parent->id;
            $parent = $parent->load('parent')->parent; // Tải tiếp quan hệ parent để tránh truy vấn riêng lẻ
        }

        return $parents;
    }

}

