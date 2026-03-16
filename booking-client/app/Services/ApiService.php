<?php
// app/Services/ApiService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ApiService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('API_URL', 'http://127.0.0.1:8000');
    }

    public function get(string $endpoint, array $params = [])
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->get($this->baseUrl . '/api/' . $endpoint, $params);

        return $response->json();
    }
}
