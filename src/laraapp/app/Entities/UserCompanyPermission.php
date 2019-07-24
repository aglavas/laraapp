<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class UserCompanyPermission extends Model
{
    /**
     * @var array
     */
    protected $table = 'user_company_permission';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'company_id', 'permission_id'];

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
     * Belongs to permission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }
}
