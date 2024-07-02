<?php

namespace Modules\Ticket\Notifications;

use Webmozart\Assert\Assert;
use Illuminate\Bus\Queueable;
use Modules\Ticket\Models\User;
use Modules\Ticket\Models\Ticket;
use Modules\Ticket\Models\TicketActivity;
use Filament\Notifications\Actions\Action;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Notifications\Notification as FilamentNotification;

class TicketStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    private Ticket $ticket;
    private TicketActivity $activity;

    /**
     * Create a new notification instance.
     *
     * @param Ticket $ticket
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
        $this->activity = Assert::notNull($this->ticket->activities->last());
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        Assert::isInstanceOf($this->ticket, Ticket::class);
        Assert::notNull($this->ticket);
        Assert::notNull($this->ticket->oldStatus);
        Assert::notNull($this->ticket->newStatus);

        return (new MailMessage)
            ->line(__('The status of ticket :ticket has been updated.', ['ticket' => $this->ticket->name]))
            ->line('- ' . __('Old status:') . ' ' . $this->activity->oldStatus->name)
            ->line('- ' . __('New status:') . ' ' . $this->activity->newStatus->name)
            ->line(__('See more details of this ticket by clicking on the button below:'))
            ->action(__('View details'), route('filament.resources.tickets.share', $this->ticket->code));
    }

    public function toDatabase(User $notifiable): array
    {
        return FilamentNotification::make()
            ->title(__('Ticket status updated'))
            ->icon('heroicon-o-ticket')
            ->body(
                fn() => __('Old status: :oldStatus - New status: :newStatus', [
                    'oldStatus' => $this->activity->oldStatus->name,
                    'newStatus' => $this->activity->newStatus->name,
                ])
            )
            ->actions([
                Action::make('view')
                    ->link()
                    ->icon('heroicon-s-eye')
                    ->url(fn() => route('filament.resources.tickets.share', $this->ticket->code)),
            ])
            ->getDatabaseMessage();
    }
}