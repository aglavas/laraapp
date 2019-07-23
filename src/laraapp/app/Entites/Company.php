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
     * Company has many products
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'company_id', 'id');
    }
}
