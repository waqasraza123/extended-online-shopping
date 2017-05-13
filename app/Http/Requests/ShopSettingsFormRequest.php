<?php

namespace App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ShopSettingsFormRequest extends FormRequest
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
        $shopId = (new Controller())->shopId;
        return [
            'shop_name' => 'required|max:255',
            'phone' => [
                "required",
                "phone",
                Rule::unique('shops')->ignore($shopId, 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                }),
            ],
            'location' => 'required',
            'market_plaza' => 'required|max:300'
        ];
    }


    /**
     * custom error messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'phone.phone' => 'Invalid Phone Format. Follow 0300-1234567 or 051-1234567.'
        ];
    }
}
