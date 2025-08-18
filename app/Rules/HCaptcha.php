<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class HCaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
            'secret' => config('services.hcaptcha.secret_key'),
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        $body = $response->json();

        if (!$body['success']) {
            $fail('The hCaptcha verification failed. Please try again.');
        }
    }
}











