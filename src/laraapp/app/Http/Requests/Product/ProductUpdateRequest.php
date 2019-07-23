<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string',
            'price' => 'numeric|between:0.00,99999.99',
            'qty' => 'integer',
            'attributes' => 'array',
            'attributes.*' => 'required|array',
            'attributes.*.id' => 'required|int|exists:attributes,id',
            'attributes.*.value' => 'required|string',
        ];
    }
}
