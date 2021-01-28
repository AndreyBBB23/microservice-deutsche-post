<?php

namespace App\Http\Requests;

class ShipmentRequest extends __BaseRequest
{
    public function rules()
    {
        $rules = [
            'weight' => 'required|integer|min:1|max:2000',
            'dimensions' => 'required|array',
            'dimensions.length' => 'nullable|integer',
            'dimensions.width' => 'nullable|integer',
            'dimensions.height' => 'nullable|integer',
            'description' => 'nullable|string',
            'sender' => 'required|array',
            'sender.name' => 'required|string',
            'sender.country_code' => 'required|string|size:2',
            'sender.postal_code' => 'required|string|max:20',
            'sender.city' => 'required|string|min:1|max:30',
            'sender.address_line' => 'required|string|min:1|max:30',
            'sender.address_line_2' => 'nullable|string|max:30',
            'sender.contact_name' => 'required|string',
            'sender.contact_email' => 'required|email|max:50',
            'sender.contact_phone' => 'required|string|max:15',
            'recipient' => 'required|array',
            'recipient.name' => 'required|string',
            'recipient.country_code' => 'required|string|size:2',
            'recipient.postal_code' => 'required|string|max:20',
            'recipient.city' => 'required|string|min:1|max:30',
            'recipient.address_line' => 'required|string|min:1|max:30',
            'recipient.address_line_2' => 'nullable|string|max:30',
            'recipient.contact_name' => 'required|string',
            'recipient.contact_email' => 'required|email|max:50',
            'recipient.contact_phone' => 'required|string|max:15',
        ];

        $rules = array_merge($rules, $this->credentialsRules());

        return $rules;
    }
}