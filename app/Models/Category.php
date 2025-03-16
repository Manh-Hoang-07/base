<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Tên bảng trong database
    protected $table = 'categories';

    // Các trường có thể gán dữ liệu hàng loạt
    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    // Định nghĩa mối quan hệ nếu cần
//    public function books()
//    {
//        return $this->hasMany(Book::class);
//    }
}
