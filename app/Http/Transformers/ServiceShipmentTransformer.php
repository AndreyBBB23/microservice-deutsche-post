<?php

namespace App\Http\Transformers;

use Stidner\Metadata\Services\Helpers\__StidnerTransformer;

class ServiceShipmentTransformer extends __StidnerTransformer
{
    public function transform(array $data): array
    {
        return [
            'customerEkp' => $data['credentials']['customerEkp'],
            'orderStatus' => 'FINALIZE',
            'paperwork' => (object)[
                'contactName' => $data['sender']['name'],
                'awbCopyCount' => 1,
                'jobReference' => 'Job ref',
                'pickupType' => 'CUSTOMER_DROP_OFF',
                'pickupLocation' => null,
                'pickupDate' => null,
                'pickupTimeSlot' => null,
                'telephoneNumber' => null,
            ],
            'items' => [
                (object)[
                    'product' => 'GMM',
                    'serviceLevel' => 'STANDARD',
                    'recipient' => $data['recipient']['name'],
                    'addressLine1' => $data['recipient']['address_line'],
                    'city' => $data['recipient']['city'],
                    'destinationCountry' => $data['recipient']['country_code'],
                    'id' => null,
                    'custRef' => null,
                    'recipientPhone' => $data['recipient']['contact_phone'],
                    'recipientFax' => $data['recipient']['contact_phone'],
                    'recipientEmail' => $data['recipient']['contact_email'],
                    'addressLine2' => $data['recipient']['address_line_2'],
                    'addressLine3' => null,
                    'state' => null,
                    'postalCode' => $data['recipient']['postal_code'],
                    'shipmentAmount' => 1,
                    'shipmentCurrency' => 'SEK',
                    'shipmentGrossWeight' => $data['weight'],
                    'returnItemWanted' => null,
                    'shipmentNaturetype' => null,
                    'contents' => [
                        (object)[
                            'contentPieceHsCode' => null,
                            'contentPieceDescription' => null,
                            'contentPieceValue' => null,
                            'contentPieceNetweight' => $data['weight'],
                            'contentPieceOrigin' => null,
                            'contentPieceAmount' => 1,
                        ]
                    ]
                ]
            ]
        ];
    }
}