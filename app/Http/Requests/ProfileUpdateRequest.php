<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      return [
        // 'name' => ['required', 'string', 'max:150'],
        // 'email' => ['required', 'string', 'email', 'max:150'],
        'phone' => ['nullable', 'string', 'max:30'],
        'gender' => ['nullable', 'string', 'in:male,female,other'],
        'bio' => ['nullable', 'string', 'max:500'],
        'avatar'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
    ];
    }
}
