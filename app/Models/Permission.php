<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = ['name', 'guard_name', 'parent_id', 'is_default'];

    // Quan hệ cha
    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_id')
            ->withDefault(['name' => 'N/A']);
    }

    // Quan hệ con
    public function children()
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }

    // Lấy tất cả quyền cha (đệ quy)
    public function getAllParents($limit = 10)
    {
        $parents = collect();
        $parent = $this->parent;
        $visitedIds = [];

        while ($parent && !in_array($parent->id, $visitedIds) && $parents->count() < $limit) {
            $parents->push($parent);
            $visitedIds[] = $parent->id;
            $parent = $parent->parent;
        }

        return $parents;
    }

    // Scope lọc các quyền cha
    public function scopeOnlyParents($query)
    {
        return $query->whereNull('parent_id');
    }

    // Scope lọc các quyền con
    public function scopeOnlyChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }

    // Load quan hệ cha-con để tránh N+1
    public static function getPermissionsWithParents()
    {
        return self::with('parent')->get();
    }
}
