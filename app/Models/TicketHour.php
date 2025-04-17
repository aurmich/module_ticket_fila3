<?php

declare(strict_types=1);

namespace Modules\Ticket\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Xot\Datas\XotData;

/**
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property int|null $activity_id
 * @property float $value
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @property Activity|null $activity
 * @property mixed $for_humans
 * @property Ticket|null $ticket
 * @property \Modules\User\Models\User|null $user
 *
 * @method static \Modules\Ticket\Database\Factories\TicketHourFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketHour withoutTrashed()
 *
 * @property-read \Modules\Fixcity\Models\Profile|null $creator
 * @property-read \Modules\Fixcity\Models\Profile|null $updater
 *
 * @mixin \Eloquent
 */
class TicketHour extends BaseModel
{
    protected $fillable = [
        'user_id', 'ticket_id', 'value', 'comment', 'activity_id',
    ];

    public function user(): BelongsTo
    {
        $user_class = XotData::make()->getUserClass();

        return $this->belongsTo($user_class, 'user_id', 'id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }

    public function forHumans(): Attribute
    {
        return new Attribute(
            get: function () {
                $seconds = $this->value * 3600;

                return CarbonInterval::seconds($seconds)->cascade()->forHumans();
            }
        );
    }
}
