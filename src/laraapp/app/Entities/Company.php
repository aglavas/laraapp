<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * Turn off timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Casts
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
    ];

    /**
     * Company has many products
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'company_id', 'id');
    }

    /**
     * Company belongs to user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
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

    /**
     * User has many labels and permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCompanyPermission()
    {
        return $this->hasMany(UserCompanyPermission::class);
    }
}
