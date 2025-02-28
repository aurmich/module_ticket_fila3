<?php

declare(strict_types=1);

namespace Modules\Ticket\Filament\Resources\TicketResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;
use Modules\Ticket\Filament\Resources\TicketResource;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\DeleteAction::make(),
    //     ];
    // }

    // public static function getRecordSubNavigation(Page $page): array
    // {
    //    dddx('a');

    //    return $page->generateNavigationItems([
    // ...
    //        Pages\ViewCustomerContact::class,
    //    ]);
    // }
}
