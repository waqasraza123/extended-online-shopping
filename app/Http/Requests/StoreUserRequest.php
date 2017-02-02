<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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

    public function mobileOrEmail(){
        return str_replace("-", "", $this->input('email_phone'));
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $data = $this->all();

        if(!is_numeric($this->mobileOrEmail()) && !empty($data['email_phone'])){
            return [
                'email_phone.required' => 'Please enter Email address.',
                'email_phone.unique' => 'This Email address has already been taken.',
                'email_phone.email' => 'Please enter correct email address.',
                'email_phone.max' => 'Please enter correct email address.',
            ];
        }
        //data has number
        else if (is_numeric($this->mobileOrEmail()) && !empty($data['email_phone'])){
            return [
                'email_phone.required' => 'Please enter Mobile number.',
                'email_phone.unique' => 'There is already a user with this Mobile number.',
                'email_phone.phone' => 'Incorrect Mobile number, Please follow 0300-1234567',
                'email_phone.max' => 'Please enter correct Mobile number.',
            ];
        }
        else{
            //if both fail
            return [
                'email_phone.required' => 'Please Enter Correct Email or Mobile.'
            ];
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = $this->all();

        //if string is not a number
        if(!is_numeric($this->mobileOrEmail()) && !empty($data['email_phone'])){
            return [
                'name' => 'required|max:255',
                'email_phone' => 'required|email|max:255|unique:users,email_phone',
                'password' => 'required|min:6|confirmed',
            ];
        }
        //it should be a proper mobile number
        elseif (is_numeric($this->mobileOrEmail()) && !empty($data['email_phone'])){
            return [
                'name' => 'required|max:255',
                'email_phone' => 'required|phone|max:12|unique:users,email_phone',
                'password' => 'required|min:6|confirmed',
            ];
        }
        else{
            return [
                'name' => 'required|max:255',
                'email_phone' => 'required|phone|max:12|unique:users,email_phone',
                'password' => 'required|min:6|confirmed',
            ];
        }
    }
}
