<?php

declare(strict_types=1);

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $ticket_id
 * @property int|null $relation_id
 * @property string $type
 * @property int $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @property Ticket|null $relation
 * @property Ticket|null $ticket
 *
 * @method static \Modules\Ticket\Database\Factories\TicketRelationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation whereRelationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRelation withoutTrashed()
 *
 * @property-read \Modules\Fixcity\Models\Profile|null $creator
 * @property-read \Modules\Fixcity\Models\Profile|null $updater
 *
 * @mixin \Eloquent
 */
class TicketRelation extends BaseModel
{
    protected $fillable = [
        'ticket_id', 'type', 'relation_id', 'sort',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }

    public function relation(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'relation_id', 'id');
    }
}
