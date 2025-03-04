<?php

declare(strict_types=1);

namespace Modules\Ticket\Filament\Resources\TicketResource\Pages;

use Cheesegrits\FilamentGoogleMaps\Actions\GoToAction;
use Cheesegrits\FilamentGoogleMaps\Actions\RadiusAction;
use Cheesegrits\FilamentGoogleMaps\Filters\MapIsFilter;
use Cheesegrits\FilamentGoogleMaps\Filters\RadiusFilter;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Components\Tab;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Modules\Ticket\Enums\TicketStatusEnum;
use Modules\Ticket\Filament\Actions\ChangeStatus;
use Modules\Ticket\Filament\Resources\TicketResource;
use Modules\Ticket\Models\Ticket;
use Modules\UI\Filament\Tables\Columns\GroupColumn;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Webmozart\Assert\Assert;

class ListTickets extends XotBaseListRecords
{
    protected static string $resource = TicketResource::class;

    // protected static ?string $heading = 'Location Map';

    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = null;

    protected static ?bool $clustering = true;

    protected static ?bool $fitToBounds = true;

    protected static ?string $mapId = 'incidents';

    protected static ?bool $filtered = true;

    protected static bool $collapsible = true;

    public ?bool $mapIsFilter = false;

    protected static ?string $markerAction = 'markerAction';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getListTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id'),

            Tables\Columns\TextColumn::make('name')
                ->searchable(),
            Tables\Columns\TextColumn::make('slug')
                ->searchable()
                // ->default(fn ($record) => dddx($record->slug)),
                ->default(function ($record) {
                    Assert::isInstanceOf($record, Ticket::class);

                    return dddx($record->slug);
                }),

            // Tables\Columns\TextColumn::make('priority.name')
            //    ->searchable(),
            // GroupColumn::make('stati')->schema([
            /* -- e' nei tabs
            Tables\Columns\TextColumn::make('status')

            ->default(function ($record) {
                $status = $record->status();
                if (null == $status) {
                    return null;
                }

                return TicketStatusEnum::from($status->name);
            }),
            */
            Tables\Columns\TextColumn::make('priority')
                ->searchable(),
            // Tables\Columns\TextColumn::make('type.name')
            //    ->searchable(),
            Tables\Columns\TextColumn::make('type')
                ->searchable(),
            // ]),
            // Tables\Columns\TextColumn::make('latitude')
            //    ->searchable(),
            // Tables\Columns\TextColumn::make('longitude')
            //    ->searchable(),
            // Tables\Columns\TextColumn::make('street')
            //     ->searchable(),
            // Tables\Columns\TextColumn::make('city')
            //     ->searchable()
            //     ->sortable(),
            // Tables\Columns\TextColumn::make('state')
            //     ->searchable()
            //     ->sortable(),
            // Tables\Columns\TextColumn::make('zip'),
            SpatieMediaLibraryImageColumn::make('images')
                // ->conversion('thumb')
                ->collection('ticket'),
        ];
    }

    public function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->url(fn (Ticket $record): string => route('ticket.view', ['slug' => $record->slug]))
                ->openUrlInNewTab(),
            Tables\Actions\EditAction::make()
                ->form($this->getFormSchema()),
            GoToAction::make()
                ->zoom(fn () => 14),
            RadiusAction::make('location'),
            ChangeStatus::make()
                ->visible(function () {
                    Assert::notNull(Filament::auth()->user());
                    Assert::notNull(Filament::auth()->user()->profile);

                    return Filament::auth()->user()->profile->isSuperAdmin();
                }),
        ];
    }

    public function getTableFilters(): array
    {
        return [
            RadiusFilter::make('location')
                ->section('Radius Filter')
                ->selectUnit(),
            MapIsFilter::make('map'),
        ];
    }

    public function getTabs(): array
    {
        foreach (Ticket::where('status', null)->get() as $item) {
            // $status = $item->status()?->name;
            $status = $item->status;
            if (null == $status) {
                $status = TicketStatusEnum::PENDING;
                $item->setStatus($status->value);
            }
            $item->status = $status;
            $item->save();
        }

        $statuses = TicketStatusEnum::cases();

        $res = [];

        foreach ($statuses as $status) {
            $k = $status->value;
            $v = Tab::make($status->getLabel())
                ->icon($status->getIcon())
                ->badge(Ticket::where('status', $status)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', $status));
            $res[$k] = $v;
        }

        return $res;
    }
}
