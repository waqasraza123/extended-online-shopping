<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreShopCredentialsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::check()){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * @return array
     */
    public function messages()
    {
        return[
            'shop_name.required' => 'Please enter shop name.',
            'shop_name.max' => 'Shop Name is too long.',
            'market_plaza.required' => 'Please enter market/plaza name.',
            'location.required' => 'Please enter location of shop.',
            'shop_phone.required' => 'Please enter Mobile number.',
            'shop_phone.unique' => 'There phone belongs to another shop, please use correct one.',
            'shop_phone.phone' => 'Incorrect Mobile number, Please follow 0300-1234567',
            'shop_phone.max' => 'Please enter correct Mobile number.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shop_name' => 'required|max:250',
            'market_plaza' => 'required',
            'location' => 'required',
            'shop_phone' => 'required|phone|max:12|unique:shops,phone'
        ];
    }
}
