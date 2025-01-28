<?php

declare(strict_types=1);

namespace Modules\Ticket\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Modules\Ticket\Enums\TicketPriorityEnum;
use Modules\Ticket\Enums\TicketStatusEnum;
use Modules\Ticket\Enums\TicketTypeEnum;
use Modules\Ticket\Notifications\TicketCreated;
use Modules\Ticket\Notifications\TicketStatusUpdated;
use Modules\Xot\Actions\File\AssetAction;
use Modules\Xot\Datas\XotData;
use Spatie\Comments\Models\Concerns\HasComments;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStatus\HasStatuses;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Webmozart\Assert\Assert;

/**
 * Modules\Ticket\Models\Ticket.
 *
 * @property string                                                                                                     $name
 * @property string                                                                                                     $slug
 * @property int                                                                                                        $id
 * @property string                                                                                                     $content
 * @property int                                                                                                        $owner_id
 * @property int|null                                                                                                   $responsible_id
 * @property int                                                                                                        $status_id
 * @property string|null                                                                                                $code
 * @property string|null                                                                                                $ticket_prefix
 * @property int                                                                                                        $order
 * @property int                                                                                                        $priority_id
 * @property int|null                                                                                                   $project_id
 * @property float|null                                                                                                 $estimation
 * @property int|null                                                                                                   $epic_id
 * @property int|null                                                                                                   $sprint_id
 * @property \Illuminate\Support\Carbon|null                                                                            $deleted_at
 * @property \Illuminate\Support\Carbon|null                                                                            $created_at
 * @property \Illuminate\Support\Carbon|null                                                                            $updated_at
 * @property int|null                                                                                                   $type_id
 * @property string|null                                                                                                $latitude
 * @property string|null                                                                                                $longitude
 * @property string|null                                                                                                $updated_by
 * @property string|null                                                                                                $created_by
 * @property string|null                                                                                                $deleted_by
 * @property \Illuminate\Database\Eloquent\Collection<int, \Modules\Ticket\Models\TicketActivity>                       $activities
 * @property int|null                                                                                                   $activities_count
 * @property \Illuminate\Database\Eloquent\Collection<int, \Modules\Ticket\Models\TicketComment>                        $comments
 * @property int|null                                                                                                   $comments_count
 * @property mixed                                                                                                      $completude_percentage
 * @property mixed                                                                                                      $estimation_for_humans
 * @property mixed                                                                                                      $estimation_in_seconds
 * @property mixed                                                                                                      $estimation_progress
 * @property \Illuminate\Database\Eloquent\Collection<int, \Modules\Ticket\Models\TicketHour>                           $hours
 * @property int|null                                                                                                   $hours_count
 * @property \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Modules\Media\Models\Media> $media
 * @property int|null                                                                                                   $media_count
 * @property \Modules\User\Models\User|null                                                                             $owner
 * @property TicketPriorityEnum|null                                                                                    $priority
 * @property \Illuminate\Database\Eloquent\Collection<int, \Modules\Ticket\Models\TicketRelation>                       $relations
 * @property int|null                                                                                                   $relations_count
 * @property \Modules\User\Models\User|null                                                                             $responsible
 * @property TicketStatusEnum|null                                                                                      $status
 * @property \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\User>                                   $subscribers
 * @property int|null                                                                                                   $subscribers_count
 * @property mixed                                                                                                      $total_logged_hours
 * @property mixed                                                                                                      $total_logged_in_hours
 * @property mixed                                                                                                      $total_logged_seconds
 * @property TicketTypeEnum|null                                                                                        $type
 *
 * @method static \Modules\Ticket\Database\Factories\TicketFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereEpicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereEstimation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     wherePriorityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereResponsibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereSprintId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereTicketPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket     withoutTrashed()
 *
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\ModelStatus\Status> $statuses
 * @property int|null                                                                  $statuses_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket currentStatus(...$names)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket otherCurrentStatus(...$names)
 *
 * @property \Modules\Xot\Contracts\ProfileContract|null $creator
 * @property \Modules\Xot\Contracts\ProfileContract|null $updater
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereType($value)
 *
 * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Comments\Models\CommentNotificationSubscription> $notificationSubscriptions
 * @property int|null                                                                                               $notification_subscriptions_count
 *
 * @mixin \Eloquent
 */
class Ticket extends BaseModel implements HasMedia
{
    use HasComments;
    use HasSlug;
    use HasStatuses;
    use InteractsWithMedia;

    protected $fillable = [
        'name', 'content', 'owner_id', 'responsible_id',
        'project_id', 'code', 'order',
        'estimation', 'epic_id', 'sprint_id',
        'latitude', 'longitude', // GEO
        // 'status_id', 'type_id', 'priority_id', //OLD
        'status', 'type', 'priority',
        'slug',
    ];

    protected $appends = [
        // 'estimationInSeconds',
        // 'estimationProgress',
    ];

    protected $casts = [
        'status' => TicketStatusEnum::class,
        'priority' => TicketPriorityEnum::class,
        'type' => TicketTypeEnum::class,
    ];

    public function casts(): array
    {
        return [
            'estimationInSeconds' => 'int',
            'estimationProgress' => 'float',
            'status' => TicketStatusEnum::class,
            'priority' => TicketPriorityEnum::class,
            'type' => TicketTypeEnum::class,
        ];
    }

    public function getIconData(): array
    {
        if (null == $this->type) {
            return [];
        }

        Assert::isInstanceOf($this->type, TicketTypeEnum::class, '['.__LINE__.']['.__FILE__.']');
        $url = $this->type->getIcon();
        $url = Str::of((string) $url)->after('heroicon-o-')->append('.svg')->toString();
        $url = app(AssetAction::class)->execute('ui::svg/'.$url);

        return [
            'url' => $url,
            'type' => 'svg',
            'scale' => [35, 35],
        ];
    }

    public static function getLatLngAttributes(): array
    {
        return [
            'lat' => 'latitude',
            'lng' => 'longitude',
        ];
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            // ->generateSlugsFrom(['type', 'name'])
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50)
            ->usingSeparator('_');
    }

    public function getSlugAttribute(?string $value): ?string
    {
        if (null != $value) {
            return $value;
        }
        $value = Str::of($this->name)->slug()->toString();
        $this->update(['slug' => $value]);

        return $value;
    }

    public static function boot()
    {
        parent::boot();
        /*
        static::creating(function (Ticket $item) {
            $project = Project::where('id', $item->project_id)->first();
            $count = Ticket::where('project_id', $project->id)->count();
            $order = $project->tickets?->last()?->order ?? -1;
            $item->code = $project->ticket_prefix . '-' . ($count + 1);
            $item->order = $order + 1;
        });
        */
        // static::created(function (Ticket $item) {
        //     Assert::notNull($item->sprint);
        //     if ($item->sprint_id && $item->sprint->epic_id) {
        //         Ticket::where('id', $item->id)->update(['epic_id' => $item->sprint->epic_id]);
        //     }
        //     foreach ($item->watchers ?? [] as $user) {
        //         $user->notify(new TicketCreated($item));
        //     }
        // });

        // static::updating(function (Ticket $item) {
        //     $old = Ticket::firstWhere(['id' => $item->id]);

        //     // Ticket activity based on status
        //     $oldStatus = $old?->status_id;
        //     if ($oldStatus != $item->status_id) {
        //         Assert::notNull(auth()->user());
        //         TicketActivity::create([
        //             'ticket_id' => $item->id,
        //             'old_status_id' => $oldStatus,
        //             'new_status_id' => $item->status_id,
        //             'user_id' => authId(),
        //         ]);
        //         /*
        //         foreach ($item->watchers as $user) {
        //             $user->notify(new TicketStatusUpdated($item));
        //         }
        //             */
        //     }

        //     // Ticket sprint update
        //     $oldSprint = $old?->sprint_id;
        //     if ($oldSprint && ! $item->sprint_id) {
        //         Ticket::where('id', $item->id)->update(['epic_id' => null]);
        //     } elseif ($item->sprint_id && $item->sprint?->epic_id) {
        //         Ticket::where('id', $item->id)->update(['epic_id' => $item->sprint->epic_id]);
        //     }
        // });
    }

    public function owner(): BelongsTo
    {
        $user_class = XotData::make()->getUserClass();

        return $this->belongsTo($user_class, 'owner_id', 'id');
    }

    public function responsible(): BelongsTo
    {
        $user_class = XotData::make()->getUserClass();

        return $this->belongsTo($user_class, 'responsible_id', 'id');
    }

    // public function status(): BelongsTo
    // {
    //     return $this->belongsTo(TicketStatus::class, 'status_id', 'id')->withTrashed();
    // }

    // public function project(): BelongsTo
    // {
    //     return $this->belongsTo(Project::class, 'project_id', 'id')->withTrashed();
    // }

    // public function type(): BelongsTo
    // {
    //    return $this->belongsTo(TicketType::class, 'type_id', 'id')->withTrashed();
    // }

    // public function priority(): BelongsTo
    // {
    //    return $this->belongsTo(TicketPriority::class, 'priority_id', 'id')->withTrashed();
    // }

    public function activities(): HasMany
    {
        return $this->hasMany(TicketActivity::class, 'ticket_id', 'id');
    }

    /*
     public function comments(): HasMany
     {
         return $this->hasMany(TicketComment::class, 'ticket_id', 'id');
     }
         */
    /*-- e' in comment
    public function subscribers(): BelongsToMany
    {
        $user_class = XotData::make()->getUserClass();

        return $this->belongsToMany($user_class, 'ticket_subscribers', 'ticket_id', 'user_id');
    }
    */
    public function relations(): HasMany
    {
        return $this->hasMany(TicketRelation::class, 'ticket_id', 'id');
    }

    public function hours(): HasMany
    {
        return $this->hasMany(TicketHour::class, 'ticket_id', 'id');
    }

    // public function epic(): BelongsTo
    // {
    //     return $this->belongsTo(Epic::class, 'epic_id', 'id');
    // }

    // public function sprint(): BelongsTo
    // {
    //     return $this->belongsTo(Sprint::class, 'sprint_id', 'id');
    // }

    // public function sprints(): BelongsTo
    // {
    //     return $this->belongsTo(Sprint::class, 'sprint_id', 'id');
    // }

    /*
    public function watchers(): Attribute
    {
        return new Attribute(
            get: function () {
                $users = $this->project->profiles;
                $users->push($this->owner);
                if ($this->responsible) {
                    $users->push($this->responsible);
                }

                return $users->unique('id');
            }
        );
    }
    */

    // public function totalLoggedHours(): Attribute
    // {
    //     return new Attribute(
    //         get: function () {
    //             $seconds = $this->hours->sum('value') * 3600;

    //             return CarbonInterval::seconds($seconds)->cascade()->forHumans();
    //         }
    //     );
    // }

    // public function totalLoggedSeconds(): Attribute
    // {
    //     return new Attribute(
    //         get: function () {
    //             return $this->hours->sum('value') * 3600;
    //         }
    //     );
    // }

    public function totalLoggedInHours(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->hours->sum('value');
            }
        );
    }

    public function estimationForHumans(): Attribute
    {
        return new Attribute(
            get: function () {
                return CarbonInterval::seconds($this->estimation_in_seconds)->cascade()->forHumans();
            }
        );
    }
    /*
    public function estimationInSeconds(): Attribute
    {
        return new Attribute(
            get: function (): ?int {
                if (! $this->estimation) {
                    return null;
                }

                return $this->estimation * 3600;
            }
        );
    }
    */

    /*
    public function estimationProgress(): Attribute
    {
        return new Attribute(
            get: function (): float {
                return (($this->totalLoggedSeconds ?? 0) / ($this->estimationInSeconds ?? 1)) * 100;
            }
        );
    }
    */

    /*
    public function completudePercentage(): Attribute
    {
        return new Attribute(
            get: fn () => $this->estimationProgress
        );
    }

    */

    /**
     * This string will be used in notifications on what a new comment
     * was made.
     */
    public function commentableName(): string
    {
        return 'Segnalazione';
    }

    /**
     * This URL will be used in notifications to let the user know
     * where the comment itself can be read.
     */
    public function commentUrl(): string
    {
        return '#';
    }
}
