<?php

namespace App\Repositories\Admin\Declarations;

use App\Models\Area;
use App\Repositories\BaseRepository;

class AreaRepository extends BaseRepository
{
    public function __construct(Area $area)
    {
        $this->model = $area;
    }
}
