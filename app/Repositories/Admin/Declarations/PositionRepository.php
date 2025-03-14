<?php

namespace App\Repositories\Admin\Declarations;

use App\Repositories\BaseRepository;
use App\Models\Position;

class PositionRepository extends BaseRepository
{
    public function __construct(Position $position)
    {
        $this->model = $position;
    }

}
