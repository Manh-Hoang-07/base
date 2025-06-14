<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Series extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'status'
    ];

    // Quan hệ 1-n với Posts (Một series có nhiều bài viết)
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // Quan hệ với Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
