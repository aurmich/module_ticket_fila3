<?php

declare(strict_types=1);

namespace Modules\Ticket\Filament\Resources\TicketResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Ticket\Filament\Resources\TicketResource;

class ManageTicketStatuses extends ManageRelatedRecords
{
    protected static string $resource = TicketResource::class;

    protected static string $relationship = 'statuses';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Statuses';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('reason'),
                Tables\Columns\TextColumn::make('created_at'),
            ])
            ->filters([
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                // Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DetachAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DetachBulkAction::make(),
                //    Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
