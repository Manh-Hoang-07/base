<?php

namespace App\Http\Controllers\Admin\Declarations\BookCopies;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Declarations\BookCopies\StoreRequest;
use App\Http\Requests\Admin\Declarations\BookCopies\UpdateRequest;
use App\Services\Admin\Declarations\BookCopyService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookCopyController extends BaseController
{
    public function __construct(BookCopyService $bookCopyService)
    {
        $this->service = $bookCopyService;
    }

    public function getService(): BookCopyService
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
        $filters = $this->getFilters($request->all());
        $options = $this->getOptions($request->all());
        $bookCopies = $this->getService()->getList($filters, $options);
        return view('admin.declarations.book_copies.index', compact('bookCopies'));
    }

    /**
     * Hiển thị form tạo bản sao sách
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        return view('admin.declarations.book_copies.create');
    }

    /**
     * Xử lý tạo bản sao sách
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $return = $this->getService()->create($request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.book_copies.index')
                ->with('success', $return['message'] ?? 'Thêm mới bản sao sách thành công.');
        }
        return redirect()->route('admin.declarations.book_copies.index')
            ->with('fail', $return['message'] ?? 'Thêm mới bản sao sách thất bại.');
    }

    /**
     * Hiển thị form sửa bản sao sách
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $bookCopy = $this->getService()->findById($id);
        return view('admin.declarations.book_copies.edit', compact('bookCopy'));
    }

    /**
     * Xử lý cập nhật bản sao sách
     * @param UpdateRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, $id): RedirectResponse
    {
        $return = $this->getService()->update($id, $request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.book_copies.index')
                ->with('success', $return['message'] ?? 'Cập nhật bản sao sách thành công.');
        }
        return redirect()->route('admin.declarations.book_copies.index')
            ->with('fail', $return['message'] ?? 'Cập nhật bản sao sách thất bại.');
    }

    /**
     * Xóa bản sao sách
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $return = $this->getService()->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.book_copies.index')
                ->with('success', $return['message'] ?? 'Xóa bản sao sách thành công.');
        }
        return redirect()->route('admin.declarations.book_copies.index')
            ->with('fail', $return['message'] ?? 'Xóa bản sao sách thất bại.');
    }
}
