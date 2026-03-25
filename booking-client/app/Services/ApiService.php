<?php
// app/Services/ApiService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ApiService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('API_URL', 'http://127.0.0.1:8000');
    }

    public function get(string $endpoint, array $params = [])
    {
        // 🔥 Добавляем /v1/ к endpoint
        $url = $this->baseUrl . '/api/v1/' . $endpoint;

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->get($url, $params);

        return $response->json();
    }

    public function post(string $endpoint, array $data = [])
    {
        $url = $this->baseUrl . '/api/v1/' . $endpoint;

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($url, $data);

        return $response->json();
    }

    public function put(string $endpoint, array $data = [])
    {
        $url = $this->baseUrl . '/api/v1/' . $endpoint;

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->put($url, $data);

        return $response->json();
    }

    public function delete(string $endpoint)
    {
        $url = $this->baseUrl . '/api/v1/' . $endpoint;

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->delete($url);

        return $response->json();
    }
}
