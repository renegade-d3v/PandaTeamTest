<?php

namespace App\Rules;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Contracts\Validation\ValidationRule;
use Symfony\Component\HttpFoundation\Response;

class ExistAnnouncement implements ValidationRule
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     * @throws GuzzleException
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $config[RequestOptions::ALLOW_REDIRECTS] = false;
        $client = new Client($config);
        $response = $client->head($value);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            $fail(__(':attribute повинне бути на дійсне оголошення'));
        }
    }
}
