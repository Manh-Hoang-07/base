<?php

namespace App\Http\Controllers\Admin\Books;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Books\BookBorrowTickets\StoreRequest;
use App\Models\BookBorrowTicket;
use App\Services\Admin\Books\BookBorrowTicketService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookBorrowTicketController extends BaseController
{
    public function __construct(BookBorrowTicketService $bookBorrowTicketService)
    {
        $this->service = $bookBorrowTicketService;
    }

    public function getService(): BookBorrowTicketService
    {
        return $this->service;
    }

    protected function getOptions(array $options): array
    {
        $options['relations'] = ['user'];
        return parent::getOptions($options);
    }

    /**
     * Hiển thị danh sách vé mượn
     * @param Request $request
     * @return View|Application|Factory
     */
    public function index(Request $request): View|Application|Factory
    {
        $filters = $this->getFilters($request->all());
        $options = $this->getOptions($request->all());
        $tickets = $this->getService()->getList($filters, $options);
        return view('admin.books.book_borrow_tickets.index', compact('tickets', 'filters', 'options'));
    }

    /**
     * Hiển thị form tạo tài khoản
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        return view('admin.books.book_borrow_tickets.create');
    }

    /**
     * Xử lý tạo phiếu mượn
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $return = $this->getService()->create($request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.books.book_borrow_tickets.index')
                ->with('success', $return['message'] ?? 'Tạo phiếu mượn thành công.');
        }
        return redirect()->route('admin.books.book_borrow_tickets.index')
            ->with('fail', $return['message'] ?? 'Tạo phiếu mượn thất bại.');
    }

//
//    public function edit($id)
//    {
//        $ticket = BookBorrowTicket::findOrFail($id);
//        $users = User::all();
//        $books = Book::all();
//        return view('admin.book_borrow_tickets.edit', compact('ticket', 'users', 'books'));
//    }
//
//    public function update(Request $request, $id)
//    {
//        $ticket = BookBorrowTicket::findOrFail($id);
//
//        $data = $request->validate([
//            'user_id' => 'required|exists:users,id',
//            'borrowed_at' => 'required|date',
//            'due_at' => 'required|date|after_or_equal:borrowed_at',
//            'books' => 'required|array',
//            'books.*.book_id' => 'required|exists:books,id',
//            'books.*.quantity' => 'required|integer|min:1'
//        ]);
//
//        DB::transaction(function () use ($data, $ticket) {
//            // Cập nhật thông tin phiếu mượn
//            $ticket->update([
//                'user_id' => $data['user_id'],
//                'borrowed_at' => $data['borrowed_at'],
//                'due_at' => $data['due_at'],
//                'note' => $data['note'] ?? null
//            ]);
//
//            // Xoá các chi tiết mượn cũ và thêm chi tiết mới
//            $ticket->details()->delete();
//
//            foreach ($data['books'] as $book) {
//                $ticket->details()->create([
//                    'book_id' => $book['book_id'],
//                    'quantity' => $book['quantity'],
//                    'note' => $book['note'] ?? null
//                ]);
//            }
//        });
//
//        return redirect()->route('admin.book-borrow-tickets.index')->with('success', 'Cập nhật phiếu mượn thành công.');
//    }
//
//    public function destroy($id)
//    {
//        $ticket = BookBorrowTicket::findOrFail($id);
//
//        DB::transaction(function () use ($ticket) {
//            // Xóa chi tiết phiếu mượn
//            $ticket->details()->delete();
//            // Xóa phiếu mượn
//            $ticket->delete();
//        });
//
//        return redirect()->route('admin.book-borrow-tickets.index')->with('success', 'Xóa phiếu mượn thành công.');
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
