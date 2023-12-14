<?php

namespace App\Models;

use App\Filters\UserFilter;
use App\Traits\ModelHasDriversTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int    $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property string $email_verified_at
 * @property string $role_id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $phone
 * @property string $remember_token
 */
class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasApiTokens;


    public function scopeFilter(Builder $builder, $request) // v2 filters, see filters folder
    {
        return (new UserFilter($request))->filter($builder);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(UserRole::class, 'role_id', 'id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id', 'id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    // public function getJWTIdentifier()
    // {
    //     return $this->getKey();
    // }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    // public function getJWTCustomClaims()
    // {
    //     return [];
    // }
}
