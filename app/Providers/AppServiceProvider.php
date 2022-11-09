<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        include_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Cashier::calculateTaxes();
        Cashier::useCustomerModel(User::class);
        Model::shouldBeStrict();

        Relation::enforceMorphMap([
            'user' => User::class,
            'customer' => Customer::class,
        ]);
    }
}
