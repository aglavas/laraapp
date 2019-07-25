<?php

namespace App\Filters;

use App\Exceptions\AttributeFormatException;
use Illuminate\Http\Request;

class ProductFilter extends QueryFilters
{

    /**
     * Create a new QueryFilters instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Filter products by name
     *
     * @param $name
     */
    public function name($name)
    {
        $this->builder->where('name', '=', $name);
    }

    /**
     * Filter product by price (equal)
     *
     * @param $price
     */
    public function priceEq($price)
    {
        $this->builder->where('price', '=', $price);
    }

    /**
     * Filter product by price (greater than)
     *
     * @param $price
     */
    public function priceGt($price)
    {
        $this->builder->where('price', '>', $price);
    }

    /**
     * Filter product by price (lesser than)
     *
     * @param $price
     */
    public function priceLt($price)
    {
        $this->builder->where('price', '<', $price);
    }

    /**
     * Filter product by qty (equal)
     *
     * @param $qty
     */
    public function qtyEq($qty)
    {
        $this->builder->where('qty', '=', $qty);
    }

    /**
     * Filter product by qty (greater than)
     *
     * @param $qty
     */
    public function qtyGt($qty)
    {
        $this->builder->where('qty', '>', $qty);
    }

    /**
     * Filter product by qty (lesser than)
     *
     * @param $qty
     */
    public function qtyLt($qty)
    {
        $this->builder->where('qty', '<', $qty);
    }

    /**e
     * Filter products by attributes
     *
     * @param $attributeData
     * @throws AttributeFormatException
     */
    public function attribute($attributeData)
    {
        try {
            $this->builder->whereHas('attributes', function ($q) use ($attributeData) {
                $explodedAllAttributes = explode(';', $attributeData);

                $first = key($explodedAllAttributes);

                foreach ($explodedAllAttributes as $key => $rawAttribute) {
                    $explodedAttributes = explode(':', $rawAttribute);

                    if ($key === $first) {
                        $q->where('attribute_id', '=', $explodedAttributes[0])
                            ->where('value', '=', $explodedAttributes[1]);
                    } else {
                        $q->orWhere('attribute_id', '=', $explodedAttributes[0])
                            ->where('value', '=', $explodedAttributes[1]);
                    }
                }
            });
        } catch (\Exception $exception) {
            throw new AttributeFormatException();
        }
    }
}
