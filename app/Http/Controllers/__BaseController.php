<?php

namespace App\Http\Controllers;

use App\Requests\__DeutschePostRequest;
use Illuminate\Http\Request;
use Stidner\Metadata\Controllers\__StidnerServiceController;
use Stidner\Metadata\Services\Response\StidnerResponse;

abstract class __BaseController extends __StidnerServiceController
{
    /**
     * @var __DeutschePostRequest
     */
    protected $apiRequest;

    /**
     * @var array
     */
    protected $credentials = [];

    public function __construct(Request $request, StidnerResponse $response, __DeutschePostRequest $apiRequest)
    {
        parent::__construct($response);

        $this->credentials = $request->input('credentials', []);

        $this->apiRequest = $apiRequest->setCredentials($this->credentials);
    }
}