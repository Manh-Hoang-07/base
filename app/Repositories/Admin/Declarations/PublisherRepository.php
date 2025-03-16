<?php

namespace App\Repositories\Admin\Declarations;

use App\Models\Publisher;
use App\Repositories\BaseRepository;

class PublisherRepository extends BaseRepository
{
    public function __construct(Publisher $publisher)
    {
        $this->model = $publisher;
    }
}
