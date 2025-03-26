<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReceptionistRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'national_id' => 'required|min:14|unique:users,national_id',
            'avatar_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'country' => 'required|string',
            'gender' => 'required|in:Male,Female',
        ];
    }
}
