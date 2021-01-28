<?php

namespace App\Requests;

use Illuminate\Support\Facades\Cache;

class CredentialsApiGetRequest extends __DeutschePostRequest
{
    /**
     * @var string
     */
    protected $url = 'https://api-qa.deutschepost.com/v1/auth/accesstoken';

    /**
     * @var bool
     */
    protected $needsAuth = false;

    public function launch(array $input = []): __DeutschePostRequest
    {
        $response = $this->makeRequest(
            'get',
            $input,
            true
        );

        $this->setAccessToken($response);
        return $this;
    }

    protected function formatErrorResponse(array $response): array
    {
        $errors = [];

        foreach ($response ?? [] as $responseData) {
            $errors[] = [$responseData];
        }

        $errors = array_merge(...$errors);

        return $errors;
    }

    protected function setAccessToken(__DeutschePostRequest $apiResponse): void
    {
        $hashedKey = "authToken-" . md5($this->clientId . $this->clientSecret);

        if (!empty($apiResponse->data)) {
            Cache::put($hashedKey, "{$apiResponse->data["token_type"]} {$apiResponse->data["access_token"]}", $apiResponse->data["expires_in"]);
        }
    }
}