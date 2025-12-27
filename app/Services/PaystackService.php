<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaystackService
{
    protected string $baseUrl = 'https://api.paystack.co';

    protected function headers()
    {
        return [
            'Authorization' => 'Bearer ' . config('services.paystack.secret'),
            'Content-Type' => 'application/json',
        ];
    }

    public function initializeTransaction(string $email, int $amount, string $callbackUrl, array $metadata = [])
    {
        return Http::withHeaders($this->headers())
            ->post($this->baseUrl . '/transaction/initialize', [
                'email' => $email,
                'amount' => $amount, // in kobo
                'callback_url' => $callbackUrl,
                'metadata' => $metadata
            ])
            ->throw()
            ->json();
    }

    public function verifyTransaction(string $reference)
    {
        return Http::withHeaders($this->headers())
            ->get($this->baseUrl . "/transaction/verify/{$reference}")
            ->throw()
            ->json();
    }
}
