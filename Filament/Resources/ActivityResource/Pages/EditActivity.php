<?php

namespace Modules\Ticket\Filament\Resources\ActivityResource\Pages;

use Modules\Ticket\Filament\Resources\ActivityResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActivity extends EditRecord
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}