<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ]);

        throw new HttpResponseException($response);
    }
    public function messages()
    {
        return [
            'name.required' => 'User Name is required!',
            'name.string' => 'User Name must be a text only!',
            'email.required' => 'Email is required!',
            'email.email' => 'Email is invalid!',
            'email.unique' => 'Email is already taken!',
            'password.required' => 'Password is required!',
            'password.min' => 'Password must be at least 8 characters!',
        ];
    }
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ];
    }
}
