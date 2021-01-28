<?php

Route::post('credentials', ['uses' => 'CredentialsController@validateCredentials']);
Route::post('shipment', ['uses' => 'ShipmentController@createOrder']);
Route::post('label/{shipment_number}', ['uses' => 'LabelController@label']);