<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreManagerRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'national_id' => 'required|unique:users,national_id',
            'avatar_image' => 'nullable|image|mimes:jpg,jpeg,png',
            'country' => 'nullable|string',
            'gender' => 'nullable|in:Male,Female',
        ];
    }
}
