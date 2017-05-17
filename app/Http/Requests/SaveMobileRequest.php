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
     * @return array
     */
    public function messages()
    {
        return [
            'current_price.discount' => 'Current Price should be less than the Old Price.',
            'current_price.eos_int' => 'Current Price can not be negative',
            'old_price.eos_int' => 'Old Price can not be negative',
            'colors.required' => 'Please Select a Color',
            'storage.required' => 'Please Select a Storage Value'
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
        $oldPrice = $this->input('old_price');

        return [
            "title" => "required|min:1|max:255",
            'stock' => 'required|digits_between:0,500',
            'old_price' => 'eos_int',
            'current_price' => 'required|eos_int|discount:'.$oldPrice,
            'brands' => 'required',
            'colors' => 'required',
            'storage' => 'required'
        ];
    }
}
