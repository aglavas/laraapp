<?php

namespace App\Http\Requests\Product;

use App\Entities\Company;
use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;

class ProductStoreRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();

        /** @var Company $company */
        $company = $this->all()['company'];

        if(!$user->can('store', $company)) {
            return false;
        }

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
