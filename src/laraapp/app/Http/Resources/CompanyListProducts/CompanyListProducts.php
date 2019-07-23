<?php

namespace App\Http\Resources\CompanyListProducts;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyListProducts extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'products' => ProductListResource::collection($this->products)
        ];
    }
}
