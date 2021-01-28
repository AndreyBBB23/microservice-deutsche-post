<?php

namespace App\Http\Requests;

class LabelRequest extends __BaseRequest
{
    public function rules()
    {
        return $this->credentialsRules();
    }
}