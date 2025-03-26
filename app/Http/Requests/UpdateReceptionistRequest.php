<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReceptionistRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|min:3|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $this->receptionist->id,
            'password' => 'nullable|string|min:6',
            'avatar_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'country' => 'sometimes|required|string',
            'gender' => 'sometimes|required|in:Male,Female',
        ];
    }
}
