<?php

declare(strict_types=1);

namespace Modules\Ticket\Filament\Widgets;

use Cheesegrits\FilamentGoogleMaps\Widgets\MapTableWidget;
use Filament\Actions\Action;
use Filament\Actions\StaticAction;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action as LoginAction;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Ticket\Enums\TicketStatusEnum;
use Modules\Ticket\Filament\Resources\TicketResource;
use Modules\Ticket\Filament\Resources\TicketResource\Pages\ListTickets;
use Modules\Ticket\Models\Ticket;

/**
 * @property \Filament\Forms\ComponentContainer $form
 */
class TicketsMapTableWidget extends MapTableWidget
{
    protected static ?string $heading = '';

    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = null;

    protected static ?bool $clustering = true;

    protected static ?bool $fitToBounds = true;

    protected static ?string $mapId = 'incidents';

    protected static ?bool $filtered = true;

    protected static bool $collapsible = false;

    public ?bool $mapIsFilter = false;

    protected static ?string $markerAction = 'markerAction';

    public function getConfig(): array
    {
        $config = parent::getConfig();

        // Disable points of interest
        //        $config['mapConfig']['styles'] = [
        //            [
        //                'featureType' => 'poi',
        //                'elementType' => 'labels',
        //                'stylers' => [
        //                    ['visibility' => 'off'],
        //                ],
        //            ],
        //        ];

        //        $config['zoom'] = 5;

        $config['center'] = [
            'lat' => 34.730369,
            'lng' => -86.586104,
        ];

        return $config;
    }

    protected function getTableQuery(): Builder
    {
        // return Ticket::query()->latest();
        return Ticket::currentStatus(TicketStatusEnum::canViewByAll())
            ->orWhere('created_by', authId())
            ->orWhere('updated_by', authId())
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return app(ListTickets::class)->getListTableColumns();
    }

    protected function getTableFilters(): array
    {
        return app(ListTickets::class)->getTableFilters();
    }

    protected function getTableRecordAction(): ?string
    {
        return 'edit';
    }

    protected function getTableHeaderActions(): array
    {
        return [];
        /*
        if (Auth::guest()) {
            return [
                LoginAction::make('Nuovo')
                    ->modalHeading('Devi loggarti per poter creare un ticket')
                    ->modalContent(view('ticket::filament.widgets.login'))
                    ->extraAttributes(['class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'])
                    ->modalWidth(MaxWidth::Medium)
                    ->modalSubmitAction(false),
            ];
        }

        return [
            CreateAction::make()
                ->form($this->getFormSchema())
                ->createAnother(false)
                ->modalSubmitAction(fn (StaticAction $action) => $action->extraAttributes(['class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded']))
                ->extraAttributes(['class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded']),
        ];
        */
    }

    protected function getTableActions(): array
    {
        return app(ListTickets::class)->getTableActions();
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }

    protected function getData(): array
    {
        $locations = $this->getRecords();

        $data = [];

        foreach ($locations as $location) {
            // dddx($location->getIconData());
            $data[] = [
                'location' => [
                    // 'lat' => $location->lat ? round(floatval($location->lat), static::$precision ?? 8) : 0,
                    // 'lng' => $location->lng ? round(floatval($location->lng), static::$precision ?? 8) : 0,
                    'lat' => floatval($location->latitude),
                    'lng' => floatval($location->longitude),
                ],
                // 'label' => $location->formatted_address,
                'label' => $location->name,
                'id' => $location->id,
                /*
                'icon' => [
                    // 'url' => url('images/dealership.svg'),
                    'url' => url('images/fire.svg'),
                    'type' => 'svg',
                    'scale' => [35, 35],
                ],
                */
                'icon' => $location->getIconData(),
            ];
        }

        return $data;
    }

    public function markerAction(): Action
    {
        return Action::make('markerAction')
            ->label('Details')
            ->infolist([
                Section::make([
                    TextEntry::make('name'),
                    TextEntry::make('street'),
                    TextEntry::make('city'),
                    TextEntry::make('state'),
                    TextEntry::make('zip'),
                    TextEntry::make('formatted_address'),
                ])
                    ->columns(3),
            ])
            ->record(function (array $arguments) {
                return array_key_exists('model_id', $arguments) ? Ticket::find($arguments['model_id']) : null;
            })
            ->modalSubmitAction(false);
    }

    public function getFormSchema(): array
    {
        return TicketResource::getFormSchema();
    }
}
