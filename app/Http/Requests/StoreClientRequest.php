<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'national_id' => 'required|unique:users,national_id|min:4',
            'country' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'avatar_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
