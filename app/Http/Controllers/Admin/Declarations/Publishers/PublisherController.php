<?php

namespace App\Http\Controllers\Admin\Declarations\Publishers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Declarations\Publishers\StoreRequest;
use App\Http\Requests\Admin\Declarations\Publishers\UpdateRequest;
use App\Services\Admin\Declarations\PublisherService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use lib\DataTable;

class PublisherController extends BaseController
{
    public function __construct(PublisherService $publisherService)
    {
        $this->service = $publisherService;
    }

    public function getService(): PublisherService
    {
        return $this->service;
    }

    /**
     * Hiển thị danh sách nhà xuất bản
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request): View|Application|Factory
    {
        $filters = $this->getFilters($request, ['name', 'code']);
        $options = $this->getOptions($request);
        $publishers = $this->getService()->getList($filters, $options);
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
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $return = $this->getService()->create($request->validated());
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
        $publisher = $this->getService()->findById($id);
        return view('admin.declarations.publishers.edit', compact('publisher'));
    }

    /**
     * Xử lý cập nhật nhà xuất bản
     * @param UpdateRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, $id): RedirectResponse
    {
        $return = $this->getService()->update($id, $request->validated());
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
        $return = $this->getService()->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.publishers.index')
                ->with('success', $return['message'] ?? 'Xóa nhà xuất bản thành công.');
        }
        return redirect()->route('admin.declarations.publishers.index')
            ->with('fail', $return['message'] ?? 'Xóa nhà xuất bản thất bại.');
    }
}
