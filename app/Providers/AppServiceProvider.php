<?php

namespace App\Providers;

use App\Models\Deposit;
use App\Models\Offering;
use App\Models\Tithe;
use App\Models\Withdrawal;
use App\Observers\DepositObserver;
use App\Observers\OfferingObserver;
use App\Observers\TitheObserver;
use App\Observers\WithdrawalObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Deposit::observe(DepositObserver::class);
        Offering::observe(OfferingObserver::class);
        Withdrawal::observe(WithdrawalObserver::class);
        Tithe::observe(TitheObserver::class);
    }
}
