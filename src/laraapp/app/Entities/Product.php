<?php

namespace App\Entities;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Filterable;

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
        'price' => 'float',
        'qty' => 'integer',
    ];

    /**
     * With array
     *
     * @var array
     */
    protected $with = ['attributes'];

    /**
     * Fillable array
     *
     * @var array
     */
    protected $fillable = ['name', 'price', 'qty'];

    /**
     * Many attributes belongs to product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute_pivot', 'product_id', 'attribute_id')
            ->withPivot('value');
    }

    /**
     * Product belongs to company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
