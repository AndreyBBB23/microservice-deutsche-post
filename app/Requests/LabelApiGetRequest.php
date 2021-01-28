<?php

namespace App\Requests;

class LabelApiGetRequest extends __DeutschePostRequest
{
    /**
     * @var string
     */
    protected $url = 'https://api-qa.deutschepost.com/dpi/shipping/v1/shipments/';

    /**
     * @var bool
     */
    protected $needsAuth = true;

    public function launch(array $input = []): __DeutschePostRequest
    {
        $this->url = $this->url . $input['awb'] . '/awblabels';

        return $this->makeRequest(
            'get',
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