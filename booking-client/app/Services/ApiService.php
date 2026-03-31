<?php
// app/Services/ApiService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class ApiService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('API_URL', 'http://127.0.0.1:8000');
    }

    /**
     * Получить токен авторизации из сессии
     */
    private function getToken(): ?string
    {
        return Session::get('api_token');
    }

    /**
     * Получить HTTP клиент с авторизацией (если есть токен)
     */
    private function getHttpClient()
    {
        $token = $this->getToken();

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        return Http::withHeaders($headers);
    }

    public function upload(string $endpoint, UploadedFile $file, array $data = [])
    {
        $url = $this->baseUrl . '/api/v1/' . $endpoint;

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getToken(),
        ])
            ->attach('avatar', $file->getContent(), $file->getClientOriginalName())
            ->post($url, $data);

        return $response->json();
    }

    public function get(string $endpoint, array $params = [])
    {
        $url = $this->baseUrl . '/api/v1/' . $endpoint;

        Log::info('API GET', ['url' => $url, 'params' => $params, 'has_token' => !empty($this->getToken())]);

        $response = $this->getHttpClient()->get($url, $params);

        return $response->json();
    }

    public function post(string $endpoint, array $data = [])
    {
        $url = $this->baseUrl . '/api/v1/' . $endpoint;

        Log::info('API POST', ['url' => $url, 'data' => $data, 'has_token' => !empty($this->getToken())]);

        $response = $this->getHttpClient()->post($url, $data);

        return $response->json();
    }

    public function put(string $endpoint, array $data = [])
    {
        $url = $this->baseUrl . '/api/v1/' . $endpoint;

        $response = $this->getHttpClient()->put($url, $data);

        return $response->json();
    }

    public function delete(string $endpoint)
    {
        $url = $this->baseUrl . '/api/v1/' . $endpoint;

        $response = $this->getHttpClient()->delete($url);

        return $response->json();
    }
}
