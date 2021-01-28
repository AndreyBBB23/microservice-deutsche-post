<?php

namespace App\Requests;

class ShipmentApiPostRequest extends __DeutschePostRequest
{
    /**
     * @var string
     */
    protected $url = 'https://api-qa.deutschepost.com/dpi/shipping/v1/orders';

    /**
     * @var bool
     */
    protected $needsAuth = true;

    public function launch(array $input = []): __DeutschePostRequest
    {
        return $this->makeRequest(
            'post',
            $input
        );
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
}