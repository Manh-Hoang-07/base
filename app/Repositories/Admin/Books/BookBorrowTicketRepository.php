<?php

namespace App\Repositories\Admin\Books;

use App\Models\BookBorrowTicket;
use App\Repositories\BaseRepository;

class BookBorrowTicketRepository extends BaseRepository
{
    public function __construct(BookBorrowTicket $bookBorrowTicket)
    {
        $this->model = $bookBorrowTicket;
    }
}
