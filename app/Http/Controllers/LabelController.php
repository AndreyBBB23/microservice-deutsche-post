<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;

class LabelController extends __BaseController
{
    public function label(LabelRequest $request, int $shipping_number)
    {
        $data = [
            'awb' => $shipping_number,
        ];

        $label = $this->apiRequest
            ->launch($data);

        return $this->response->withData($label->getData());
    }
}