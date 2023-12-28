<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RegistrationRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Введіть вашу електронну пошту, це обов\'язкове поле',
            'password.required' => 'Введіть пароль, це обов\'язкове поле',
            'email.unique' => 'Користувач з такою поштою вже зареєстрований.',
            'email.email' => 'Електронна пошта повинна бути валідною',
        ];
    }

    /**
     * @param Validator $validator
     * @return void
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        if ($this->is('api/*') || $this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'errors' => $validator->errors(),
                'status' => true
            ], 422));
        }

        parent::failedValidation($validator);
    }
}
