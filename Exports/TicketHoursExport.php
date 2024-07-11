<?php

declare(strict_types=1);

namespace Modules\Ticket\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Ticket\Models\Ticket;
use Modules\Ticket\Models\TicketHour;

class TicketHoursExport implements FromCollection, WithHeadings
{
    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function headings(): array
    {
        return [
            '#',
            'Ticket',
            'User',
            'Time',
            'Hours',
            'Activity',
            'Date',
            'Comment',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->ticket->hours
            ->map(fn (TicketHour $item) => [
                '#' => $item->ticket->code,
                'ticket' => $item->ticket->name,
                'user' => $item->user->name,
                'time' => $item->forHumans,
                'hours' => number_format($item->value, 2, ',', ' '),
                'activity' => $item->activity ? $item->activity->name : '-',
                'date' => $item->created_at->format(__('Y-m-d g:i A')),
                'comment' => $item->comment,
            ]);
    }
}
