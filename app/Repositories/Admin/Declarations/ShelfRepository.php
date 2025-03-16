<?php

namespace App\Repositories\Admin\Declarations;

use App\Models\Shelf;
use App\Repositories\BaseRepository;

class ShelfRepository extends BaseRepository
{
    public function __construct(Shelf $shelf)
    {
        $this->model = $shelf;
    }
}
