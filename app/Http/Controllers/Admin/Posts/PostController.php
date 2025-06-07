<?php

namespace App\Http\Controllers\Admin\Posts;

use App\Http\Controllers\BaseController;

use App\Services\Admin\Posts\PostService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

use Illuminate\Http\Request;

class PostController extends BaseController
{
    public function __construct(PostService $postService)
    {
        $this->service = $postService;
    }

    public function getService(): PostService
    {
        return $this->service;
    }

    /**
     * Hiển thị danh sách bài đăng
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request): View|Application|Factory
    {
        return view('admin.posts.index', [
            'filters' => $this->getFilters($request->all()),
            'options' => $this->getOptions($request->all())
        ]);
    }

    /**
     * Hiển thị form tạo bài đăng
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        return view('admin.posts.create');
    }

    /**
     * Hiển thị form sửa bài đăng
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $post = $this->getService()->findById($id);
        return view('admin.posts.edit', compact('post'));
    }
}
