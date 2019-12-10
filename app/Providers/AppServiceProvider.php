<?php

namespace App\Providers;

use Dividebuy\Payment\Contracts\Payment;
use Dividebuy\Payment\Contracts\Payment as PaymentGatewayInterface;
use Illuminate\Support\ServiceProvider;
use Modules\Payment\Gateway\Sagepay\PaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->singleton(Payment::class, PaymentGateway::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
