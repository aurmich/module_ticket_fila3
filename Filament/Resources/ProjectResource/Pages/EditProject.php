<?php

namespace Modules\Ticket\Filament\Resources\ProjectResource\Pages;

use Modules\Ticket\Filament\Resources\ProjectResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('kanban')
                ->label(
                    fn ()
                    => ($this->record->type === 'scrum' ? __('Scrum board') : __('Kanban board'))
                )
                ->icon('heroicon-o-view-columns')
                ->color('gray')
                ->url(function () {
                    if ($this->record->type === 'scrum') {
                        return route('filament.pages.scrum/{project}', ['project' => $this->record->id]);
                    } else {
                        return route('filament.pages.kanban/{project}', ['project' => $this->record->id]);
                    }
                }),

            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
