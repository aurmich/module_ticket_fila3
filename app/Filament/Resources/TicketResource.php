<?php

/**
 * ---.
 */

declare(strict_types=1);

namespace Modules\Ticket\Filament\Resources;

use Dotswan\MapPicker\Fields\Map;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Support\Str;
use Modules\Ticket\Enums\TicketPriorityEnum;
use Modules\Ticket\Enums\TicketTypeEnum;
use Modules\Ticket\Filament\Resources\TicketResource\Pages;
use Modules\Ticket\Models\Ticket;
use Modules\Ticket\Rules\FilterCoordinatesInRadius;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Webmozart\Assert\Assert;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;

class TicketResource extends XotBaseResource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-europe-africa';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->schema([
                    TextInput::make('name')
                        ->hiddenLabel()
                        ->placeholder(__('ticket::ticket.title.placeholder') . '*')
                        ->columnSpanFull() // Occupa tutta la larghezza disponibile
                        ->required()
                        ->maxLength(255)
                        ->afterStateUpdated(function (Set $set, Get $get, string $state) {
                            if ($get('slug')) {
                                return;
                            }
                            $set('slug', Str::slug($state));
                        })
                        ->extraAttributes([
                            'style' => ''
                        ]),
                    // Slug
                    TextInput::make('slug')
                        ->columnSpanFull() // Anche lo slug occupa tutta la larghezza disponibile
                        ->required()
                        ->hidden()
                        ->extraAttributes(['class' => 'max-w-full', 'style' => 'padding: 0; margin: 0;']), // Rimozione del padding e margini

                    Select::make('type')
                        ->hiddenLabel()
                        ->placeholder(__('ticket::ticket.type.placeholder') . '*')
                        ->searchable()
                        ->options(TicketTypeEnum::class)
                        ->columnSpanFull(),

                    Select::make('priority')
                        ->hiddenLabel()
                        ->placeholder(__('ticket::ticket.priorities.label'))
                        ->searchable()
                        ->options(TicketPriorityEnum::class)
                        ->default(TicketPriorityEnum::default())
                        ->columnSpanFull(),

                    Textarea::make('content')
                        ->hiddenLabel()
                        ->placeholder(__('ticket::ticket.content.placeholder') . '**')
                        ->rows(2)
                        ->cols(10)
                        ->helperText(__('ticket::ticket.content.helper_text')),

                    TextInput::make('latitude')
                        ->hidden(
                            function () {
                                Assert::notNull(Filament::auth()->user());
                                Assert::notNull(Filament::auth()->user()->profile);
                                return Filament::auth()->user()->profile->isSuperAdmin() ? false : true;
                            }
                        )
                        ->readOnly(),
                    TextInput::make('longitude')
                        ->hidden(
                            function () {
                                Assert::notNull(Filament::auth()->user());
                                Assert::notNull(Filament::auth()->user()->profile);
                                return Filament::auth()->user()->profile->isSuperAdmin() ? false : true;
                            }
                        )
                        ->readOnly(),

                    // Map
                    Map::make('location')
                        ->label(__('ticket::ticket.your-location'))
                        ->columnSpanFull()
                        ->default(['lat' => 40.4168, 'lng' => -3.7038])
                        ->afterStateUpdated(fn(Set $set, ?array $state) => $state ? $set('latitude', $state['lat']) && $set('longitude', $state['lng']) : null)
                        ->liveLocation()
                        ->showFullscreenControl()
                        ->showZoomControl()
                        ->draggable()
                        ->tilesUrl('https://tile.openstreetmap.de/{z}/{x}/{y}.png')
                        ->zoom(15)
                        ->detectRetina()
                        ->showMyLocationButton(),

                    SpatieMediaLibraryFileUpload::make('images')
                        ->label(__('ticket::ticket.insert-images'))
                        ->collection('ticket')
                        ->disk('uploads')
                        ->multiple()
                        ->required()
                        ->columnSpanFull(),
                ])
                ->columnSpanFull()
                ->extraAttributes(['class' => 'w-full max-w-full mx-auto']),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getFormSchema());
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'view' => Pages\ViewTicket::route('/{record}'),
            'statuses' => Pages\ManageTicketStatuses::route('/{record}/statuses'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            // Pages\ListTickets::class,
            // Pages\CreateTicket::class,
            Pages\ViewTicket::class,
            Pages\EditTicket::class,
            Pages\ManageTicketStatuses::class,
        ]);
    }
}
