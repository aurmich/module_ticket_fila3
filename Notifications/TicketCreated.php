<?php

declare(strict_types=1);

namespace Modules\Ticket\Notifications;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Ticket\Models\Ticket;
use Modules\User\Models\User;
use Webmozart\Assert\Assert;

class TicketCreated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private readonly Ticket $ticket)
    {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(User $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail(User $notifiable)
    {
        Assert::notNull($this->ticket);
        Assert::notNull($this->ticket->project);
        Assert::notNull($this->ticket->owner);
        Assert::notNull($this->ticket->responsible);
        Assert::notNull($this->ticket->type);
        Assert::notNull($this->ticket->priority);
        Assert::notNull($this->ticket->status);

        return (new MailMessage())
            ->line(__('A new ticket has just been created.'))
            ->line('- '.__('Ticket name:').' '.$this->ticket->name)
            ->line('- '.__('Project:').' '.$this->ticket->project->name)
            ->line('- '.__('Owner:').' '.$this->ticket->owner->name)
            ->line('- '.__('Responsible:').' '.$this->ticket->responsible->name)
            ->line('- '.__('Status:').' '.$this->ticket->status->name)
            ->line('- '.__('Type:').' '.$this->ticket->type->name)
            ->line('- '.__('Priority:').' '.$this->ticket->priority->name)
            ->line(__('See more details of this ticket by clicking on the button below:'))
            ->action(__('View details'), route('filament.resources.tickets.share', $this->ticket->code));
    }

    public function toDatabase(User $notifiable): array
    {
        return FilamentNotification::make()
            ->title(__('New ticket created'))
            ->icon('heroicon-o-ticket')
            ->body(fn () => $this->ticket->name)
            ->actions([
                Action::make('view')
                    ->link()
                    ->icon('heroicon-s-eye')
                    ->url(fn () => route('filament.resources.tickets.share', $this->ticket->code)),
            ])
            ->getDatabaseMessage();
    }
}