<?php

namespace App\Providers;

use App\Http\Controllers\CredentialsController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ShipmentController;
use App\Requests\CredentialsApiGetRequest;
use App\Requests\LabelApiGetRequest;
use App\Requests\ShipmentApiPostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Stidner\Metadata\Services\Response\StidnerResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);

        $this->app->bind(CredentialsController::class, function (\Illuminate\Foundation\Application $app) {
            return new CredentialsController(
                $app->make(Request::class),
                $app->make(StidnerResponse::class),
                $app->make(CredentialsApiGetRequest::class)
            );
        });

        $this->app->bind(ShipmentController::class, function (\Illuminate\Foundation\Application $app) {
            return new ShipmentController(
                $app->make(Request::class),
                $app->make(StidnerResponse::class),
                $app->make(ShipmentApiPostRequest::class)
            );
        });

        $this->app->bind(LabelController::class, function (\Illuminate\Foundation\Application $app) {
            return new LabelController(
                $app->make(Request::class),
                $app->make(StidnerResponse::class),
                $app->make(LabelApiGetRequest::class)
            );
        });
    }
}