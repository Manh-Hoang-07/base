<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;

    protected $table = 'authors';

    protected $fillable = [
        'name',
        'nationality',
        'biography',
        'birth_date',
        'death_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];

    // Một tác giả có nhiều sách
//    public function books()
//    {
//        return $this->hasMany(Book::class);
//    }
}
