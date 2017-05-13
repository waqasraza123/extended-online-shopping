<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserSettingsFormRequest extends FormRequest
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
        $user = Auth::user();
        return [
            'name' => 'required|max:255',
            'user_name' => 'required|email|max:255|unique:users,email_phone,'.$user->id,
            'phone' => 'phone|max:12|unique:users,phone,'.$user->id,
            'password' => 'between:6,20',
            'password_confirmation' => 'sometimes:same:password'
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
