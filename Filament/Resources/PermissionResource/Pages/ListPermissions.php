<?php

namespace Modules\Ticket\Filament\Resources\PermissionResource\Pages;

use Modules\Ticket\Filament\Resources\PermissionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
