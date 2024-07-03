<?php

namespace Modules\Ticket\Filament\Resources;

use Modules\Ticket\Filament\Resources\TimesheetResource\Pages;
use Modules\Ticket\Filament\Resources\TimesheetResource\RelationManagers;
use Modules\Ticket\Models\Activity;
use Modules\Ticket\Models\TicketHour;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class TimesheetResource extends Resource
{
    protected static ?string $model = TicketHour::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('Timesheet');
    }

    public static function getPluralLabel(): ?string
    {
        return static::getNavigationLabel();
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Timesheet');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('List timesheet data');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Select::make('activity_id')
                            ->label(__('Activity'))
                            ->searchable()
                            ->reactive()
                            ->options(function ($get, $set) {
                                return Activity::all()->pluck('name', 'id')->toArray();
                            }),
                        TextInput::make('value')
                            ->label(__('Time to log'))
                            ->numeric()
                            ->required(),

                        Textarea::make('comment')
                            ->label(__('Comment'))
                            ->rows(3),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Owner'))
                    ->sortable()
                    ->formatStateUsing(fn($record) => view('ticket::components.user-avatar', ['user' => $record->user]))
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimesheet::route('/'),
            'edit' => Pages\EditTimesheet::route('/{record}/edit'),
        ];
    }
}
