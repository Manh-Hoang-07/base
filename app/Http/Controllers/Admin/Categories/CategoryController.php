<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\BaseController;

use App\Services\Admin\Categories\CategoryService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function __construct(CategoryService $categoryService)
    {
        $this->service = $categoryService;
    }

    public function getService(): CategoryService
    {
        return $this->service;
    }

    /**
     * Hiển thị danh sách danh mục
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request): View|Application|Factory
    {
        return view('admin.categories.index', [
            'filters' => $this->getFilters($request->all()),
            'options' => $this->getOptions($request->all())
        ]);
    }

    /**
     * Hiển thị form tạo danh mục
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        $categories = $this->getService()->getList();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Hiển thị form sửa danh mục
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $category = $this->getService()->findById($id);
        $categories = $this->getService()->getAll();
        return view('admin.categories.edit', compact('category', 'categories'));
    }
}
