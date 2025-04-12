<?php

namespace App\Http\Controllers\Admin\Declarations\Books;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Declarations\Books\StoreRequest;
use App\Http\Requests\Admin\Declarations\Books\UpdateRequest;
use App\Services\Admin\Declarations\BookService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookController extends BaseController
{
    public function __construct(BookService $bookService)
    {
        $this->service = $bookService;
    }

    public function getService(): BookService
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
        $books = $this->getService()->getList($filters, $options);
        return view('admin.declarations.books.index', compact('books'));
    }

    /**
     * Hiển thị form tạo sách
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        return view('admin.declarations.books.create');
    }

    /**
     * Xử lý tạo sách
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $return = $this->getService()->create($request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.books.index')
                ->with('success', $return['message'] ?? 'Thêm mới sách thành công.');
        }
        return redirect()->route('admin.declarations.books.index')
            ->with('fail', $return['message'] ?? 'Thêm mới sách thất bại.');
    }

    /**
     * Hiển thị form sửa sách
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $book = $this->getService()->findById($id);
        return view('admin.declarations.books.edit', compact('book'));
    }

    /**
     * Xử lý cập nhật sách
     * @param UpdateRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, $id): RedirectResponse
    {
        $return = $this->getService()->update($id, $request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.books.index')
                ->with('success', $return['message'] ?? 'Cập nhật sách thành công.');
        }
        return redirect()->route('admin.declarations.books.index')
            ->with('fail', $return['message'] ?? 'Cập nhật sách thất bại.');
    }

    /**
     * Xóa sách
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $return = $this->getService()->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.books.index')
                ->with('success', $return['message'] ?? 'Xóa sách thành công.');
        }
        return redirect()->route('admin.declarations.books.index')
            ->with('fail', $return['message'] ?? 'Xóa sách thất bại.');
    }
}
