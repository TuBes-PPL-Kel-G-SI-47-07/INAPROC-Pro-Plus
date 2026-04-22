<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        
    {
return [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', \Illuminate\Validation\Rule::unique(User::class)->ignore($this->user()->id)],
        'phone_number' => ['nullable', 'string', 'max:15'], // Tambahkan ini [cite: 491]
        'address' => ['nullable', 'string'],               // Tambahkan ini [cite: 491]
        'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // Maks 2MB [cite: 503]
        'position' => ['nullable', 'string', 'max:255'],
    ];
    }
}
}
