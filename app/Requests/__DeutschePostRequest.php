<?php

namespace App\Requests;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Stidner\Metadata\Exceptions\Common\CannotPerformActionException;
use Stidner\Metadata\Traits\StidnerLoggerTrait;

abstract class __DeutschePostRequest
{
    use StidnerLoggerTrait;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var array
     */
    protected $headers = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    /**
     * @var string
     */
    protected $url;

    /**
     * @var bool
     */
    protected $needsAuth = false;

    /**
     * @var bool
     */
    protected $failOnError = true;

    /**
     * @var int
     */
    protected $statusCode = 0;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $errors = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    abstract public function launch(array $input = []): __DeutschePostRequest;

    public function setCredentials(array $credentials)
    {
        $this->clientId = $credentials['clientId'] ?? null;

        $this->clientSecret = $credentials['clientSecret'] ?? null;

        return $this;
    }

    public function setFailOnError(bool $failOnError)
    {
        $this->failOnError = $failOnError;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function isSuccessful(): bool
    {
        return $this->statusCode < 300;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getDataAttribute(string $handle, $default = null)
    {
        return $this->data[$handle] ?? $default;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    protected function makeRequest(string $method, array $input = [], $validate = false)
    {
        $this->logger()->message('Deutsche Post API request', [
            'request' => static::class,
            'input' => $input
        ]);

        $inputKey = $method === 'get' ? 'query' : 'json';

        if ($validate) {
            $this->headers["Authorization"] = "Basic " . base64_encode("{$this->clientId}:{$this->clientSecret}");
        }

        if ($this->needsAuth === true) {

            $token = $this->getAccessToken();

            $this->headers = array_merge($this->headers, [
                'Authorization' => $token
            ]);
        }

        $response = $this->client->request(
            $method,
            $this->url,
            [
                $inputKey => $input,
                'headers' => $this->headers,
                'http_errors' => false
            ]
        );

        $this->statusCode = $response->getStatusCode();

        $originalResponse = $response->getBody()->getContents();

        $responseArray = json_decode($originalResponse, true) ?? [];

        if ($this->isSuccessful() === false) {
            if (empty($responseArray) === false) {
                $this->errors = $this->formatErrorResponse($responseArray);
            } else {
                $this->errors = [$originalResponse];
            }
        } elseif ($this->detectNonExceptionError($responseArray) === false) {
            $this->data = $responseArray;
            if (!is_array($originalResponse)) {
                $this->data['label'] = base64_encode($originalResponse);
            }
        }

        if ($this->isSuccessful() === true) {
            $this->logger()->message('Deutsche Post API successful response', [
                'request' => static::class,
                'input' => $this->data
            ]);
        } else {
            $this->logger()->error('Deutsche Post API error response', [
                'request' => static::class,
                'status_code' => $this->statusCode,
                'errors' => $this->getErrors()
            ]);
        }

        if ($this->failOnError === true && $this->isSuccessful() === false) {
            throw new CannotPerformActionException([], $this->getErrors());
        }

        return $this;
    }

    public function getAccessToken(): ?string
    {
        $hashedKey = "authToken-" . md5($this->clientId . $this->clientSecret);

        if (!Cache::has($hashedKey)) {
            $this->headers["Authorization"] = "Basic " . base64_encode("{$this->clientId}:{$this->clientSecret}");

            $response = $this->client->request(
                'get',
                'https://api-qa.deutschepost.com/v1/auth/accesstoken',
                [
                    'headers' => $this->headers,
                    'http_errors' => false
                ]
            );
            $originalResponse = $response->getBody()->getContents();
            $responseArray = json_decode($originalResponse, true) ?? [];

            Cache::put($hashedKey, "{$responseArray["token_type"]} {$responseArray["access_token"]}", $responseArray["expires_in"]);

            return Cache::get($hashedKey);
        }

        return Cache::get($hashedKey);
    }

    protected function detectNonExceptionError(array $response): bool
    {
        return false;
    }

    protected function formatErrorResponse(array $response): array
    {
        return $response;
    }
}