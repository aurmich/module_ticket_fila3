<?php

declare(strict_types=1);

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Ticket\Notifications\TicketCommented;
use Modules\Xot\Datas\XotData;
use Webmozart\Assert\Assert;

/**
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_by
 * @property Ticket|null $ticket
 * @property \Modules\User\Models\User|null $user
 *
 * @method static \Modules\Ticket\Database\Factories\TicketCommentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketComment withoutTrashed()
 *
 * @property-read \Modules\Fixcity\Models\Profile|null $creator
 * @property-read \Modules\Fixcity\Models\Profile|null $updater
 *
 * @mixin \Eloquent
 */
class TicketComment extends BaseModel
{
    protected $fillable = [
        'user_id', 'ticket_id', 'content',
    ];

    public static function boot()
    {
        parent::boot();
        /*
        static::created(function (TicketComment $item) {
            Assert::notNull($item->ticket);
            foreach ($item->ticket->watchers as $user) {
                $user->notify(new TicketCommented($item));
            }
        });
        */
    }

    public function user(): BelongsTo
    {
        $user_class = XotData::make()->getUserClass();

        return $this->belongsTo($user_class, 'user_id', 'id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }
}
