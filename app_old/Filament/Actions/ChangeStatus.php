<?php

declare(strict_types=1);

namespace Modules\Ticket\Filament\Actions;

use Webmozart\Assert\Assert;
use Modules\Ticket\Models\Ticket;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Modules\Ticket\Enums\TicketStatusEnum;
use Modules\Ticket\Actions\ChangeStatus as ActionChangeStatus;

class ChangeStatus extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->translateLabel()
            ->action(
                function (Ticket $record, array $data): void {
                    
                    Assert::string($data['status']);
                    Assert::string($data['reason']);
                    
                    app(ActionChangeStatus::class)->execute($record, $data['status'], $data['reason']);
                }
            )
            ->form([
                Select::make('status')
                    ->options(TicketStatusEnum::class)
                    ->required(),
                TextInput::make('reason')
                    ->label('Per quale motivo stai modificando lo stato?')
                    ->helperText('La motivazione verrà visualizzata nel dettaglio del ticket')
                    ->required(),
            ])
            ->label('Change Status')
            ->icon('ui-status')
            ->tooltip('Change Status')
            ->modalHeading('Modifica Status')
            // ->requiresConfirmation()
            ->modalSubmitActionLabel('Modifica');
    }

    public static function getDefaultName(): ?string
    {
        return 'changeStatus';
    }
}
