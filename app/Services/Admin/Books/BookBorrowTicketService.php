<?php

namespace App\Services\Admin\Books;

use App\Models\BookBorrowTicket;
use App\Repositories\Admin\Books\BookBorrowTicketRepository;
use App\Repositories\Admin\Declarations\AreaRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use lib\DataTable;

class BookBorrowTicketService extends BaseService
{
    public function __construct(BookBorrowTicketRepository $bookBorrowTicketRepository)
    {
        $this->repository = $bookBorrowTicketRepository;
    }

    protected function getRepository(): BookBorrowTicketRepository
    {
        return $this->repository;
    }

    /**
     * Tạo mới khu vực
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Thêm mới phiếu mượn thất bại'
        ];
        try {
            $keys = ['user_id', 'borrowed_at', 'due_at', 'note', 'books'];
            DB::beginTransaction();
            if (($insertData = DataTable::getChangeData($data, $keys))
                && ($ticket = $this->getRepository()->create([
                    'user_id' => $insertData['user_id'] ?? '',
                    'borrowed_at' => $insertData['borrowed_at'] ?? '',
                    'due_at' => $insertData['due_at'] ?? '',
                    'note' => $insertData['note'] ?? null,
                ]))
            ) {
                // Duyệt qua danh sách sách được mượn
                $details = [];

                foreach ($insertData['books'] as $book) {
                    $details[] = [
                        'book_id' => $book['book_id'],
                        'quantity' => $book['quantity'],
                        'note' => $book['note'] ?? null,
                    ];
                }

                $ticket->details()->createMany($details);
                DB::commit();
                $return['success'] = true;
                $return['messages'] = 'Thêm mới phiếu mượn thành công.';
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return $return;
    }

    /**
     * Cập nhật khu vực
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật khu vực thất bại'
        ];
        $keys = ['name', 'code', 'location', 'type', 'description', 'capacity', 'status'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($updateData)
            && ($role = $this->getRepository()->findById($id))
            && $this->getRepository()->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật khu vực thành công';
        }
        return $return;
    }
}
