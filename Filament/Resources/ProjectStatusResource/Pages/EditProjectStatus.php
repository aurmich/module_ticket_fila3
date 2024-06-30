<?php

namespace Modules\Ticket\Filament\Resources\ProjectStatusResource\Pages;

use Modules\Ticket\Filament\Resources\ProjectStatusResource;
use Modules\Ticket\Models\ProjectStatus;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectStatus extends EditRecord
{
    protected static string $resource = ProjectStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record->is_default) {
            ProjectStatus::where('id', '<>', $this->record->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
    }
}
