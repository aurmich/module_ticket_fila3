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

class TicketResource extends XotBaseResource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-europe-africa';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make()
                ->schema([
                    // Ticket Name
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

                    // Ticket Type
                    Forms\Components\Select::make('type')
                        ->hiddenLabel()
                        ->placeholder(__('ticket::ticket.type.placeholder'). '*')
                        ->searchable()
                        ->options(TicketTypeEnum::class)
                        ->columnSpanFull()
                        ->extraAttributes(['class' => 'max-w-full', 'style' => 'padding: 0; margin: 0;']), // Rimozione del padding e margini

                    // Ticket Priority
                    Forms\Components\Select::make('priority')
                        ->hiddenLabel()
                        ->placeholder(__('ticket::ticket.priorities.label'))
                        ->searchable()
                        ->options(TicketPriorityEnum::class)
                        ->default(TicketPriorityEnum::default())
                        ->columnSpanFull()
                        ->extraAttributes(['class' => 'max-w-full', 'style' => 'padding: 0; margin: 0;']), // Rimozione del padding e margini

                    // Ticket Content (RichEditor)
                    // Forms\Components\RichEditor::make('content')
                    //     ->label(__('ticket::ticket.content.label'))
                    //     ->required()
                    //     ->columnSpanFull()
                    //     ->extraAttributes(['class' => 'max-w-full', 'style' => 'padding: 0; margin: 0;']), // Rimozione del padding e margini
                    Textarea::make('content')
                        ->hiddenLabel()
                        ->placeholder(__('ticket::ticket.content.placeholder') . '**')
                        ->rows(2)
                        ->cols(10)
                        ->helperText(__('ticket::ticket.content.helper_text')),

                    // Hidden Latitude and Longitude
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

                    // Map Section
                    Map::make('location')
                        ->label(__('ticket::ticket.your-location'))
                        ->columnSpanFull() // Occupare l'intera larghezza disponibile
                        ->default([
                            'lat' => 40.4168,
                            'lng' => -3.7038,
                        ])
                        ->afterStateUpdated(function (Set $set, ?array $state): void {
                            if (is_array($state)) {
                                $set('latitude', $state['lat']);
                                $set('longitude', $state['lng']);
                            }
                        })
                        ->afterStateHydrated(function ($state, $record, Set $set): void {
                            $set('location', ['lat' => $record?->latitude, 'lng' => $record?->longitude]);
                        })
                        ->rules([new FilterCoordinatesInRadius()])
                        ->liveLocation()
                        ->showMarker(false) // https://github.com/dotswan/filament-map-picker/pull/51
                        ->markerColor('#22c55eff')
                        ->showFullscreenControl()
                        ->showZoomControl()
                        ->draggable()
                        ->tilesUrl('https://tile.openstreetmap.de/{z}/{x}/{y}.png')
                        ->zoom(15)
                        ->detectRetina()
                        ->showMyLocationButton()
                        ->extraAttributes(['class' => 'max-w-full', 'style' => 'min-height: 300px; padding: 0; margin: 0;']),

                    // Image Upload
                    SpatieMediaLibraryFileUpload::make('images')
                        ->label(__('ticket::ticket.insert-images'))
                        ->collection('ticket')
                        ->directory('ticket')
                        ->disk('uploads')
                        ->responsiveImages()
                        ->multiple()
                        ->required()
                        ->columnSpanFull()
                        ->extraAttributes(['class' => 'max-w-full', 'style' => 'padding: 0; margin: 0;'])
                        ->helperText(__('ticket::ticket.images-helper-text')),
                ])
                ->columns(1) // Imposta il layout su una colonna
                ->extraAttributes(['class' => 'w-full max-w-full mx-auto', 'style' => 'padding: 0; margin: 0; !important;']), // Rimozione padding e margine
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getFormSchema());
    }

    public static function getRelations(): array
    {
        return [
        ];
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
