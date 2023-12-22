<?php

namespace App\Http\Requests;

use App\Rules\ExistAnnouncement;
use App\Rules\ValidDomain;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrackPriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', Rule::exists('users', 'email')->where(function ($query) {
                $query->where('email_verified_at', '!=', null);
            })],
            'announcement' => ['required', new ValidDomain, new ExistAnnouncement],
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'announcement' => 'Посилання',
            'email' => 'Електронна пошта'
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'announcement.required' => 'Поле обов\'язкове до заповнення',
            'email.required' => 'Поле обов\'язкове до заповнення',
            'email.email' => ':attribute повинна бути валідною',
            'email.exists' => ':attribute повинна бути верифікованю.',
        ];
    }
}
