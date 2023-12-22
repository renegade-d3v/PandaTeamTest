<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDomain implements ValidationRule
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $domain = $this->getDomain($value);

        if (!str_ends_with($domain, 'olx.ua')) {
            $fail(__(':attribute повинне бути на ОЛХ оголошення'));
        }
    }

    /**
     * @param mixed $value
     * @return string|null
     */
    protected function getDomain(mixed $value): ?string
    {
        $pattern = '/(?:https?:\/\/)?(?:www\.)?([a-zA-Z0-9-]+(?:\.[a-zA-Z]{2,})+)/';

        preg_match($pattern, $value, $matches);

        return $matches[1] ?? null;
    }
}
