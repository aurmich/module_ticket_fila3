<?php

declare(strict_types=1);

namespace Modules\Ticket\Models;

// use GeneaLabs\LaravelModelCaching\CachedBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\User\Models\BaseProfile as UserBaseProfile;
use Modules\User\Models\Permission;
// use Modules\Xot\Models\Traits\WidgetTrait;
use Modules\User\Models\Role;
use Modules\User\Models\SocialiteUser;

/**
 * Modules\Ticket\Models\Profile.
 *
 * @property string|null                              $full_name
 * @property Collection<int, Permission>              $permissions
 * @property int|null                                 $permissions_count
 * @property Collection<int, Role>                    $roles
 * @property int|null                                 $roles_count
 * @property \Modules\Xot\Contracts\UserContract|null $user
 * @property int                                      $id
 * @property string|null                              $first_name
 * @property string|null                              $last_name
 * @property Carbon|null                              $created_at
 * @property Carbon|null                              $updated_at
 * @property string|null                              $updated_by
 * @property string|null                              $created_by
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUpdatedBy($value)
 *
 * @property Collection<int, TicketHour>                                                                                   $hours
 * @property int|null                                                                                                      $hours_count
 * @property Collection<int, SocialiteUser>                                                                                $socials
 * @property int|null                                                                                                      $socials_count
 * @property Collection<int, Ticket>                                                                                       $ticketsOwned
 * @property int|null                                                                                                      $tickets_owned_count
 * @property Collection<int, Ticket>                                                                                       $ticketsResponsible
 * @property int|null                                                                                                      $tickets_responsible_count
 * @property mixed                                                                                                         $total_logged_in_hours
 * @property string                                                                                                        $user_id
 * @property string|null                                                                                                   $email
 * @property string                                                                                                        $credits
 * @property string|null                                                                                                   $slug
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null                                                        $extra
 * @property Carbon|null                                                                                                   $deleted_at
 * @property string|null                                                                                                   $deleted_by
 * @property string                                                                                                        $avatar
 * @property Collection<int, \Modules\User\Models\DeviceUser>                                                              $deviceUsers
 * @property int|null                                                                                                      $device_users_count
 * @property Collection<int, \Modules\User\Models\Device>                                                                  $devices
 * @property int|null                                                                                                      $devices_count
 * @property \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Modules\Media\Models\Media>    $media
 * @property int|null                                                                                                      $media_count
 * @property Collection<int, \Modules\User\Models\DeviceUser>                                                              $mobileDeviceUsers
 * @property int|null                                                                                                      $mobile_device_users_count
 * @property Collection<int, \Modules\User\Models\Device>                                                                  $mobileDevices
 * @property int|null                                                                                                      $mobile_devices_count
 * @property \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property int|null                                                                                                      $notifications_count
 * @property Collection<int, \Modules\User\Models\Team>                                                                    $teams
 * @property int|null                                                                                                      $teams_count
 * @property string|null                                                                                                   $user_name
 *
 * @method static \Modules\Ticket\Database\Factories\ProfileFactory                      factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Profile                          whereCredits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile                          whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile                          whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile                          whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile                          whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile                          whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile                          whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Models\BaseProfile withExtraAttributes()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Models\BaseProfile withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Models\BaseProfile withoutRole($roles, $guard = null)
 *
 * @property \Modules\User\Models\DeviceUser      $pivot
 * @property \Modules\User\Models\Membership      $membership
 * @property \Modules\Fixcity\Models\Profile|null $creator
 * @property \Modules\Fixcity\Models\Profile|null $updater
 *
 * @mixin \Eloquent
 */
class Profile extends UserBaseProfile
{
    /** @var string */
    protected $connection = 'ticket';

    /** @var list<string> */
    protected $fillable = ['id', 'user_id', 'phone', 'email', 'bio'];

    // ------- RELATIONSHIP ----------

    // public function projectsOwning(): HasMany
    // {
    //     return $this->hasMany(Project::class, 'owner_id', 'user_id');
    // }

    // public function projectsAffected(): BelongsToMany
    // {
    //     return $this->belongsToMany(Project::class, 'project_users', 'user_id', 'project_id')->withPivot(['role']);
    // }

    // public function favoriteProjects(): BelongsToMany
    // {
    //     return $this->belongsToMany(Project::class, 'project_favorites', 'user_id', 'project_id');
    // }

    public function ticketsOwned(): HasMany
    {
        return $this->hasMany(Ticket::class, 'owner_id', 'user_id');
    }

    public function ticketsResponsible(): HasMany
    {
        return $this->hasMany(Ticket::class, 'responsible_id', 'user_id');
    }

    public function socials(): HasMany
    {
        return $this->hasMany(SocialiteUser::class, 'user_id', 'user_id');
    }

    public function hours(): HasMany
    {
        return $this->hasMany(TicketHour::class, 'user_id', 'user_id');
    }

    public function totalLoggedInHours(): Attribute
    {
        return new Attribute(
            get: fn () => $this->hours->sum('value')
        );
    }
}// end model
