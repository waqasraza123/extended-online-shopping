<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class SaveMobileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::check())
            return true;
        else
            return false;
    }



    public function messages()
    {
        return [
            'current_price.discount' => 'Discount value should be less than the old value.'
        ];
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //this is discounted price
        $currentPrice = $this->input('current_price');

        return [
            "title" => "required|min:3|max:255",
            'stock' => 'required|digits_between:0,500',
            'old_price' => 'required|integer',
            'current_price' => 'discount:'.$currentPrice,
            'brands' => 'required'
        ];
    }
}
