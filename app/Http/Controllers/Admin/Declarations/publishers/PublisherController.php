<?php

namespace App\Http\Controllers\Admin\Declarations\publishers;

use App\Http\Controllers\BaseController;
use App\Services\Admin\Declarations\PublisherService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use lib\DataTable;

class PublisherController extends BaseController
{
    protected PublisherService $publisherService;

    public function __construct(PublisherService $publisherService)
    {
        $this->publisherService = $publisherService;
    }

    /**
     * Hiển thị danh sách nhà xuất bản
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request): View|Application|Factory
    {
        $filters = DataTable::getFiltersData($request->all(), ['name', 'code']);
        $options = DataTable::getOptionsData($request->all());
        $publishers = $this->publisherService->getList($filters, $options);
        return view('admin.declarations.publishers.index', compact('publishers'));
    }

    /**
     * Hiển thị form tạo nhà xuất bản
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        return view('admin.declarations.publishers.create');
    }

    /**
     * Xử lý tạo nhà xuất bản
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
        ]);
        $return = $this->publisherService->create($request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.publishers.index')
                ->with('success', $return['message'] ?? 'Thêm mới nhà xuất bản thành công.');
        }
        return redirect()->route('admin.declarations.publishers.index')
            ->with('fail', $return['message'] ?? 'Thêm mới nhà xuất bản thất bại.');
    }

    /**
     * Hiển thị form sửa nhà xuất bản
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $publisher = $this->publisherService->findById($id);
        return view('admin.declarations.publishers.edit', compact('publisher'));
    }

    /**
     * Xử lý cập nhật nhà xuất bản
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required'
        ]);
        $return = $this->publisherService->update($id, $request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.publishers.index')
                ->with('success', $return['message'] ?? 'Cập nhật nhà xuất bản thành công.');
        }
        return redirect()->route('admin.declarations.publishers.index')
            ->with('fail', $return['message'] ?? 'Cập nhật nhà xuất bản thất bại.');
    }

    /**
     * Xóa nhà xuất bản
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $return = $this->publisherService->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.publishers.index')
                ->with('success', $return['message'] ?? 'Xóa nhà xuất bản thành công.');
        }
        return redirect()->route('admin.declarations.publishers.index')
            ->with('fail', $return['message'] ?? 'Xóa nhà xuất bản thất bại.');
    }
}
