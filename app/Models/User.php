<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


/**
 * Class User
 * @package App\Models
 *
 * @mixin Builder
 */
class User extends Authenticatable implements AuthenticatableContract, CanResetPasswordContract
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $dates = [
        'registered_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(COMMENT::class, 'from_user');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(POST::class, 'id');
    }

    public function canPost(): bool
    {
        $role = $this->role;

        if ($role == 'author' || $role == 'admin') {
            return true;
        }
        return false;
    }

    public function isAdmin(): bool
    {
        $role = $this->role;
        if ($role == 'admin') {
            return true;
        }
        return false;
    }
}
