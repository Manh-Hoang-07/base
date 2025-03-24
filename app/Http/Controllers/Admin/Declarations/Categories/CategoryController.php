<?php

namespace App\Http\Controllers\Admin\Declarations\Categories;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Declarations\Categories\StoreRequest;
use App\Http\Requests\Admin\Declarations\Categories\UpdateRequest;
use App\Services\Admin\Declarations\CategoryService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use lib\DataTable;

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
        $filters = DataTable::getFiltersData($request->all(), ['name', 'code']);
        $options = DataTable::getOptionsData($request->all());
        $categories = $this->getService()->getList($filters, $options);
        return view('admin.declarations.categories.index', compact('categories'));
    }

    /**
     * Hiển thị form tạo danh mục
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        $categories = $this->getService()->getList();
        return view('admin.declarations.categories.create', compact('categories'));
    }

    /**
     * Xử lý tạo danh mục
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $return = $this->getService()->create($request->validated());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.categories.index')
                ->with('success', $return['message'] ?? 'Thêm mới danh mục thành công.');
        }
        return redirect()->route('admin.declarations.categories.index')
            ->with('fail', $return['message'] ?? 'Thêm mới danh mục thất bại.');
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
        return view('admin.declarations.categories.edit', compact('category', 'categories'));
    }

    /**
     * Xử lý cập nhật danh mục
     * @param UpdateRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, $id): RedirectResponse
    {
        $return = $this->getService()->update($id, $request->validated());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.categories.index')
                ->with('success', $return['message'] ?? 'Cập nhật danh mục thành công.');
        }
        return redirect()->route('admin.declarations.categories.index')
            ->with('fail', $return['message'] ?? 'Cập nhật danh mục thất bại.');
    }

    /**
     * Xóa danh mục
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $return = $this->getService()->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.categories.index')
                ->with('success', $return['message'] ?? 'Xóa danh mục thành công.');
        }
        return redirect()->route('admin.declarations.categories.index')
            ->with('fail', $return['message'] ?? 'Xóa danh mục thất bại.');
    }
}
