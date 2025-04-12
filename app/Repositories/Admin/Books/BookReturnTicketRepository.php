<?php

namespace App\Repositories\Admin\Books;

use App\Models\BookReturnTicket;
use App\Repositories\BaseRepository;

class BookReturnTicketRepository extends BaseRepository
{
    public function __construct(BookReturnTicket $bookReturnTicket)
    {
        $this->model = $bookReturnTicket;
    }
}
