<?php

declare(strict_types=1);

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Xot\Datas\XotData;

/**
 * @property int $id
 * @property int $user_id
 * @property int $ticket_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @property Ticket|null $ticket
 *
 * @method static \Modules\Ticket\Database\Factories\TicketSubscriberFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketSubscriber withoutTrashed()
 *
 * @property \Modules\User\Models\User|null $user
 * @property-read \Modules\Fixcity\Models\Profile|null $creator
 * @property-read \Modules\Fixcity\Models\Profile|null $updater
 *
 * @mixin \Eloquent
 */
class TicketSubscriber extends BaseModel
{
    protected $fillable = [
        'user_id', 'ticket_id',
    ];

    public function user(): BelongsTo
    {
        $user_class = XotData::make()->getUserClass();

        return $this->belongsTo($user_class, 'user_id', 'id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'user_id', 'id');
    }
}
