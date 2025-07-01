<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Http\Models\User;
class UsersRequestAdd extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => 'string|max:255|required',
            'username' => 'string|max:255|required|unique:users,username,'.$id.',_id',
            'email' => 'string|email|max:255|required|unique:users,email,'.$id.',_id',
            'password' => 'string|min:6|nullable',
            'role' => 'required|string|in:admin,user',
            'pic' => 'nullable|image|mimes:jpeg,png,jpg,svg,gif|max:4072'
        ];
    }

    
}
