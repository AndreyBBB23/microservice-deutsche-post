<?php

namespace App\Http\Requests;

use Stidner\Metadata\Http\Requests\__ServiceHttpRequest;

abstract class __BaseRequest extends __ServiceHttpRequest
{
    protected function credentialsRules(): array
    {
        return [
            'credentials' => 'required|array',
            'credentials.clientId' => 'required|string',
            'credentials.clientSecret' => 'required|string',
            'credentials.customerEkp' => 'required|string',
        ];
    }
}