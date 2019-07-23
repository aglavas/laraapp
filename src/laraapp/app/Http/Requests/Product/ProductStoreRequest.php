<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'name' => 'required|string',
            'price' => 'required|numeric|between:0.00,99999.99',
            'qty' => 'required|integer',
            'attributes' => 'required|array',
            'attributes.*' => 'required|array',
            'attributes.*.id' => 'required|int|exists:attributes,id',
            'attributes.*.value' => 'required|string',
        ];
    }
}
