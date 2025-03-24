<?php

namespace App\Http\Controllers\Admin\Declarations\Authors;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Declarations\Authors\StoreRequest;
use App\Http\Requests\Admin\Declarations\Authors\UpdateRequest;
use App\Services\Admin\Declarations\AuthorService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use lib\DataTable;

class AuthorController extends BaseController
{
    public function __construct(AuthorService $authorService)
    {
        $this->service = $authorService;
    }

    public function getService(): AuthorService
    {
        return $this->service;
    }

    /**
     * Hiển thị danh sách tác giả
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request): View|Application|Factory
    {
        $filters = DataTable::getFiltersData($request->all(), ['name', 'code']);
        $options = DataTable::getOptionsData($request->all());
        $authors = $this->getService()->getList($filters, $options);
        return view('admin.declarations.authors.index', compact('authors'));
    }

    /**
     * Hiển thị form tạo tác giả
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        return view('admin.declarations.authors.create');
    }

    /**
     * Xử lý tạo tác giả
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $return = $this->getService()->create($request->validated());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.authors.index')
                ->with('success', $return['message'] ?? 'Thêm mới tác giả thành công.');
        }
        return redirect()->route('admin.declarations.authors.index')
            ->with('fail', $return['message'] ?? 'Thêm mới tác giả thất bại.');
    }

    /**
     * Hiển thị form sửa tác giả
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $author = $this->getService()->findById($id);
        return view('admin.declarations.authors.edit', compact('author'));
    }

    /**
     * Xử lý cập nhật tác giả
     * @param UpdateRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, $id): RedirectResponse
    {
        $return = $this->getService()->update($id, $request->validated());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.authors.index')
                ->with('success', $return['message'] ?? 'Cập nhật tác giả thành công.');
        }
        return redirect()->route('admin.declarations.authors.index')
            ->with('fail', $return['message'] ?? 'Cập nhật tác giả thất bại.');
    }

    /**
     * Xóa tác giả
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $return = $this->getService()->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.authors.index')
                ->with('success', $return['message'] ?? 'Xóa tác giả thành công.');
        }
        return redirect()->route('admin.declarations.authors.index')
            ->with('fail', $return['message'] ?? 'Xóa tác giả thất bại.');
    }
}
