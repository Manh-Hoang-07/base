<?php

namespace App\Repositories\Admin\Declarations;

use App\Models\Series;
use App\Repositories\BaseRepository;

class SeriesRepository extends BaseRepository
{
    public function __construct(Series $series)
    {
        $this->model = $series;
    }
}
