<?php

namespace App\Repositories\Admin\Declarations;

use App\Models\Author;
use App\Repositories\BaseRepository;

class AuthorRepository extends BaseRepository
{
    public function __construct(Author $author)
    {
        $this->model = $author;
    }
}
