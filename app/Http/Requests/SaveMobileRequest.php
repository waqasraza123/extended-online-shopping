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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $currentPrice = $this->input('current_price');
        return [
            "title" => "required|min:3|max:255",
            'stock' => 'required|digits_between:0,500',
            'current_price' => 'required|integer',
            'discount_price' => 'discount:'.$currentPrice,
            'product_image' => 'required|image',
            'colors' => 'required',
            'brands' => 'required'
        ];
    }
}
