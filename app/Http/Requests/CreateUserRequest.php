<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Class level contants, to avoid SONAR LINT errors/warnings.
     */
    const REQUIRED = 'required';

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
            'username' => self::REQUIRED . '|unique:users',
            'email' => self::REQUIRED . '|unique:users,email|email',
            'password' => self::REQUIRED . '|confirmed|min:6',
            'password_confirmation' => self::REQUIRED,
            'first_name' => self::REQUIRED,
            'last_name' => self::REQUIRED,
            'address' => self::REQUIRED,
            'house_number' => self::REQUIRED . '|integer',
            'postal_code' => self::REQUIRED . '|integer',
            'city' => self::REQUIRED,
            'telephone_number' => self::REQUIRED . '|unique:users|digits:10',
        ];
    }
}
