<?php

namespace App\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Traits\CompanyAuthorization;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, CompanyAuthorization;

    /**
     * Turn off timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * Hash pass
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * User has many labels and permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCompanyPermission()
    {
        return $this->hasMany(UserCompanyPermission::class);
    }

    /**
     * User has many labels and permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCompanyRole()
    {
        return $this->hasMany(UserCompanyRole::class);
    }
}
