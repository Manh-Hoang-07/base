<?php

namespace App\Http\Controllers\Admin\Declarations\Series;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Declarations\Series\StoreRequest;
use App\Http\Requests\Admin\Declarations\Series\UpdateRequest;
use App\Models\Series;
use App\Services\Admin\Declarations\SeriesService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use lib\DataTable;

class SeriesController extends BaseController
{
    public function __construct(SeriesService $seriesService)
    {
        $this->service = $seriesService;
    }

    public function getService(): SeriesService
    {
        return $this->service;
    }

    /**
     * Hiển thị danh sách series
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request): View|Application|Factory
    {
        $filters = $this->getFilters($request, ['name', 'code']);
        $options = $this->getOptions($request);
        $series = $this->getService()->getList($filters, $options);
        return view('admin.declarations.series.index', compact('series'));
    }

    /**
     * Hiển thị form tạo series
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        return view('admin.declarations.series.create');
    }

    /**
     * Xử lý tạo series
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $return = $this->getService()->create($request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.series.index')
                ->with('success', $return['message'] ?? 'Thêm mới series thành công.');
        }
        return redirect()->route('admin.declarations.series.index')
            ->with('fail', $return['message'] ?? 'Thêm mới series thất bại.');
    }

    /**
     * Hiển thị form sửa series
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $series = $this->getService()->findById($id);
        return view('admin.declarations.series.edit', compact('series'));
    }

    /**
     * Xử lý cập nhật series
     * @param UpdateRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, $id): RedirectResponse
    {
        $return = $this->getService()->update($id, $request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.series.index')
                ->with('success', $return['message'] ?? 'Cập nhật series thành công.');
        }
        return redirect()->route('admin.declarations.series.index')
            ->with('fail', $return['message'] ?? 'Cập nhật series thất bại.');
    }

    /**
     * Xóa series
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $return = $this->getService()->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.series.index')
                ->with('success', $return['message'] ?? 'Xóa series thành công.');
        }
        return redirect()->route('admin.declarations.series.index')
            ->with('fail', $return['message'] ?? 'Xóa series thất bại.');
    }
}
