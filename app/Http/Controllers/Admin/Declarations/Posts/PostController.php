<?php

namespace App\Http\Controllers\Admin\Declarations\Posts;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Services\Admin\Declarations\PostService;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    public function __construct(PostService $postService)
    {
        $this->service = $postService;
    }
}
