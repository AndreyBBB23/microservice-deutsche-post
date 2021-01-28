<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShipmentRequest;
use App\Http\Transformers\ServiceShipmentTransformer;

class ShipmentController extends __BaseController
{
    public function createOrder(ShipmentRequest $request, ServiceShipmentTransformer $shipmentTransformer)
    {
        $order = $this->apiRequest
            ->launch($shipmentTransformer->transform($request->all()))
            ->getDataAttribute('shipments', []);

        $orderData = $order[0]['awb'];

        $output = [
            'shipment_number' => $orderData,
        ];

        return $this->response->withData($output);
    }
}