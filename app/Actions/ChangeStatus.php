<?php

declare(strict_types=1);

namespace Modules\Ticket\Actions;

use Modules\Ticket\Enums\TicketStatusEnum;
use Modules\Ticket\Models\Ticket;

class ChangeStatus
{
    public function execute(Ticket $ticket, string $status, string $reason): void
    {
        $ticket->setStatus($status, $reason);
        $ticket->status = TicketStatusEnum::from($status);
        $ticket->save();
    }
}
