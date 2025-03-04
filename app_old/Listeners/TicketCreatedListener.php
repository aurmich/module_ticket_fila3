<?php

declare(strict_types=1);

namespace Modules\Ticket\Listeners;

use Modules\Ticket\Actions\ChangeStatus;
use Modules\Ticket\Enums\TicketStatusEnum;
use Modules\Ticket\Events\TicketCreatedEvent;

class TicketCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(TicketCreatedEvent $event): void
    {
        $status = TicketStatusEnum::PENDING;
        app(ChangeStatus::class)->execute($event->ticket, $status->value, 'creazione nuovo ticket');

        // $ticket = $event->ticket;
        // $ticket->setStatus($status->value, 'creazione nuovo ticket');
        // $ticket->status = $status;
        // $ticket->save();
    }
}
