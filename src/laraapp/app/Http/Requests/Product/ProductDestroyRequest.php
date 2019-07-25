<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;
use App\Entities\Company;

class ProductDestroyRequest extends BaseRequest
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

        if (!$user->can('delete', $company)) {
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

        ];
    }
}
