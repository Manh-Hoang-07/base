<?php

namespace App\Http\Controllers\Admin\Books;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Users\Users\AssignRequest;
use App\Http\Requests\Admin\Users\Users\StoreRequest;
use App\Http\Requests\Admin\Users\Users\UpdateRequest;
use App\Services\Admin\Books\BookReturnTicketService;
use App\Services\Admin\Users\UserService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use lib\DataTable;
use Spatie\Permission\Models\Role;

class BookReturnTicketController extends BaseController
{
    public function __construct(BookReturnTicketService $bookReturnTicketService)
    {
        $this->service = $bookReturnTicketService;
    }

    public function getService(): BookReturnTicketService
    {
        return $this->service;
    }

    public function index()
    {
        $tickets = BookReturnTicket::with('user')->latest()->paginate(10);
        return view('admin.book_return_tickets.index', compact('tickets'));
    }

    public function create()
    {
        $users = User::all();
        $borrowDetails = BookBorrowTicketDetail::with(['book', 'ticket'])
            ->whereColumn('quantity', '>', 'returned_quantity')
            ->get();

        return view('admin.book_return_tickets.create', compact('users', 'borrowDetails'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'returned_at' => 'required|date',
            'details' => 'required|array',
            'details.*.borrow_detail_id' => 'required|exists:book_borrow_ticket_details,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data) {
            $ticket = BookReturnTicket::create([
                'user_id' => $data['user_id'],
                'returned_at' => $data['returned_at'],
                'note' => $data['note'] ?? null,
            ]);

            foreach ($data['details'] as $detail) {
                $borrowDetail = BookBorrowTicketDetail::findOrFail($detail['borrow_detail_id']);

                $borrowDetail->returned_quantity += $detail['quantity'];

                if ($borrowDetail->returned_quantity >= $borrowDetail->quantity) {
                    $borrowDetail->status = 'returned';
                } elseif ($borrowDetail->returned_quantity > 0) {
                    $borrowDetail->status = 'partial';
                }

                $borrowDetail->save();

                $ticket->details()->create([
                    'book_borrow_ticket_detail_id' => $borrowDetail->id,
                    'book_id' => $borrowDetail->book_id,
                    'quantity' => $detail['quantity'],
                    'note' => $detail['note'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.book-return-tickets.index')->with('success', 'Tạo phiếu trả thành công.');
    }

    public function edit($id)
    {
        $ticket = BookReturnTicket::findOrFail($id);
        $users = User::all();
        $borrowDetails = BookBorrowTicketDetail::with(['book', 'ticket'])
            ->whereColumn('quantity', '>', 'returned_quantity')
            ->get();

        return view('admin.book_return_tickets.edit', compact('ticket', 'users', 'borrowDetails'));
    }

    public function update(Request $request, $id)
    {
        $ticket = BookReturnTicket::findOrFail($id);

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'returned_at' => 'required|date',
            'details' => 'required|array',
            'details.*.borrow_detail_id' => 'required|exists:book_borrow_ticket_details,id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, $ticket) {
            $ticket->update([
                'user_id' => $data['user_id'],
                'returned_at' => $data['returned_at'],
                'note' => $data['note'] ?? null,
            ]);

            $ticket->details()->delete();

            foreach ($data['details'] as $detail) {
                $borrowDetail = BookBorrowTicketDetail::findOrFail($detail['borrow_detail_id']);

                $borrowDetail->returned_quantity += $detail['quantity'];

                if ($borrowDetail->returned_quantity >= $borrowDetail->quantity) {
                    $borrowDetail->status = 'returned';
                } elseif ($borrowDetail->returned_quantity > 0) {
                    $borrowDetail->status = 'partial';
                }

                $borrowDetail->save();

                $ticket->details()->create([
                    'book_borrow_ticket_detail_id' => $borrowDetail->id,
                    'book_id' => $borrowDetail->book_id,
                    'quantity' => $detail['quantity'],
                    'note' => $detail['note'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.book-return-tickets.index')->with('success', 'Cập nhật phiếu trả thành công.');
    }

    public function destroy($id)
    {
        $ticket = BookReturnTicket::findOrFail($id);

        DB::transaction(function () use ($ticket) {
            $ticket->details()->delete();
            $ticket->delete();
        });

        return redirect()->route('admin.book-return-tickets.index')->with('success', 'Xóa phiếu trả thành công.');
    }

//    /**
//     * Hiển thị danh sách tài khoản
//     * @param Request $request
//     * @return View|Application|Factory
//     */
//    public function index(Request $request): View|Application|Factory
//    {
//        $filters = $this->getFilters($request->all());
//        $options = $this->getOptions($request->all());
//        $users = $this->getService()->getList($filters, $options);
//        return view('admin.users.index', compact('users', 'filters', 'options'));
//    }
//
//    /**
//     * Hiển thị form tạo tài khoản
//     * @return View|Application|Factory
//     */
//    public function create(): View|Application|Factory
//    {
//        return view('admin.users.create');
//    }
//
//    /**
//     * Xử lý tạo tài khoản
//     * @param StoreRequest $request
//     * @return RedirectResponse
//     */
//    public function store(StoreRequest $request): RedirectResponse
//    {
//        $return = $this->getService()->create($request->all());
//        if (!empty($return['success'])) {
//            return redirect()->route('admin.users.index')
//                ->with('success', $return['message'] ?? 'Tạo tài khoản thành công.');
//        }
//        return redirect()->route('admin.users.index')
//            ->with('fail', $return['message'] ?? 'Tạo tài khoản thất bại.');
//    }
//
//    /**
//     * Hiển thị form chỉnh sửa tài khoản
//     * @param $id
//     * @return View|Application|Factory
//     */
//    public function edit($id): View|Application|Factory
//    {
//        $user = $this->getService()->findById($id);
//        return view('admin.users.edit', compact('user'));
//    }
//
//    /**
//     * Xử lý chỉnh sửa tài khoản
//     * @param UpdateRequest $request
//     * @param $id
//     * @return RedirectResponse
//     */
//    public function update(UpdateRequest $request, $id): RedirectResponse
//    {
//        $return = $this->getService()->update($id, $request->all());
//        if (!empty($return['success'])) {
//            return redirect()->route('admin.users.index')
//                ->with('success', $return['message'] ?? 'Cập nhật tài khoản thành công.');
//        }
//        return redirect()->route('admin.users.index')
//            ->with('fail', $return['message'] ?? 'Cập nhật tài khoản thất bại.');
//    }
//
//    /**
//     * Xử lý xóa tài khoản
//     * @param $id
//     * @return RedirectResponse
//     */
//    public function delete($id): RedirectResponse
//    {
//        $return = $this->getService()->delete($id);
//        if (!empty($return['success'])) {
//            return redirect()->route('admin.users.index')
//                ->with('success', $return['message'] ?? 'Xóa tài khoản thành công.');
//        }
//        return redirect()->route('admin.users.index')
//            ->with('fail', $return['message'] ?? 'Xóa tài khoản thất bại.');
//    }

}
