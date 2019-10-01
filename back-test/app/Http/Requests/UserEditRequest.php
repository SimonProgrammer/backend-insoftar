<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserEditRequest extends FormRequest
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
        return [
            'id' => 'required|numeric|exists:users,id',
            'identification' => [ 'required','numeric',Rule::unique('users')->ignore($this->request->get('id'))],
            'name' => 'required|string|max:50',
            'email' => [ 'required','email',Rule::unique('users')->ignore($this->request->get('id'))],
            'phone' => 'required|numeric',
            'password' => 'required|string|min:6'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'identification.required' => 'Identification is required',
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'phone.required' => 'Phone is required',
            'password.required' => 'Password is required!'
        ];
    }
}
