<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class UserCompanyRole extends Model
{
    /**
     * @var array
     */
    protected $table = 'user_company_role';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'company_id', 'role_id'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Belongs to user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Belongs to company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * Belongs to role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
