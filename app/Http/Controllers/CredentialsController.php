<?php

namespace App\Http\Controllers;

use App\Http\Requests\CredentialsRequest;

class CredentialsController extends __BaseController
{
    public function validateCredentials(CredentialsRequest $request)
    {
        $this->apiRequest->setFailOnError(false)->launch();

        $output = [
            'clientId' => true,
            'clientSecret' => true,
            'customerEkp' => true,
        ];

        foreach ($this->apiRequest->getErrors() as $error) {
            if (\is_string($error)) {
                $output['clientId'] = false;
                $output['clientSecret'] = false;
                $output['customerEkp'] = false;
            }
        }

        return $this->response->withData($output);
    }
}