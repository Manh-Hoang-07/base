<?php

namespace App\Repositories\Admin\Declarations;

use App\Models\Area;
use App\Models\Post;
use App\Repositories\BaseRepository;

class PostRepository extends BaseRepository
{
    public function __construct(Post $post)
    {
        $this->model = $post;
    }
}
