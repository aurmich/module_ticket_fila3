<?php

declare(strict_types=1);

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Xot\Datas\XotData;

/**
 * @property int $id
 * @property int $ticket_id
 * @property int $old_status_id
 * @property int $new_status_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @property Ticket|null $ticket
 * @property \Modules\User\Models\User|null $user
 *
 * @method static \Modules\Ticket\Database\Factories\TicketActivityFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity whereNewStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity whereOldStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketActivity withoutTrashed()
 *
 * @property-read \Modules\Fixcity\Models\Profile|null $creator
 * @property-read \Modules\Fixcity\Models\Profile|null $updater
 *
 * @mixin \Eloquent
 */
class TicketActivity extends BaseModel
{
    protected $fillable = [
        'ticket_id',
        'old_status_id',
        'new_status_id',
        'user_id',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id')->withTrashed();
    }

    /*
    public function oldStatus(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'old_status_id', 'id')->withTrashed();
    }

    public function newStatus(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'new_status_id', 'id')->withTrashed();
    }
    */
    public function user(): BelongsTo
    {
        $user_class = XotData::make()->getUserClass();

        return $this->belongsTo($user_class, 'user_id', 'id');
    }
}
