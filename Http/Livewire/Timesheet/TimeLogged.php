<?php

declare(strict_types=1);

namespace Modules\Ticket\Http\Livewire\Timesheet;

use Filament\Tables;
use Livewire\Component;
use Modules\Ticket\Models\Ticket;

use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TimeLogged extends Component implements HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Ticket $ticket;

    protected function getFormModel(): Model|string|null
    {
        return $this->ticket;
    }

    protected function getTableQuery(): Builder
    {
        return $this->ticket->hours()->getQuery();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('user.name')
                ->label(__('Owner'))
                ->sortable()
                ->formatStateUsing(fn($record) => view('components.user-avatar', ['user' => $record->user]))
                ->searchable(),

            Tables\Columns\TextColumn::make('value')
                ->label(__('Hours'))
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('comment')
                ->label(__('Comment'))
                ->limit(50)
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('activity.name')
                ->label(__('Activity'))
                ->sortable(),

            Tables\Columns\TextColumn::make('ticket.name')
                ->label(__('Ticket'))
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label(__('Created at'))
                ->dateTime()
                ->sortable()
                ->searchable(),
        ];
    }
}
