<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Http\Models\Post;
class PostsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    
     protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // $id = $this->route('id');

        return [
        'title' => 'required|string|max:50',
        'content' => 'required|string',
        'category' => 'nullable|string',
        'tags' => 'nullable|string',
        'status' => 'required|string|in:published,draft',
        'media' => 'nullable|array', // optional
       'media.*' => 'nullable|file|mimes:jpeg,jpg,png,mp4,mov,avi,mp3,gog|max:20480',
        ];
    }

    
}
