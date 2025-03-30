<?php

namespace App\Repositories\Admin\Declarations;

use App\Models\Book;
use App\Repositories\BaseRepository;

class BookRepository extends BaseRepository
{
    public function __construct(Book $book)
    {
        $this->model = $book;
    }
}
