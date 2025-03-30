<?php

namespace App\Repositories\Admin\Declarations;

use App\Models\BookCopy;
use App\Repositories\BaseRepository;

class BookCopyRepository extends BaseRepository
{
    public function __construct(BookCopy $bookCopy)
    {
        $this->model = $bookCopy;
    }
}
