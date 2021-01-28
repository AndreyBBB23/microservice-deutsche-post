<?php

namespace App\Http\Requests;

class CredentialsRequest extends __BaseRequest
{
    public function rules()
    {
        return $this->credentialsRules();
    }
}