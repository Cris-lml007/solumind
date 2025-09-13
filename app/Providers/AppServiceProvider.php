<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        Gate::define('product-permission',function(User $user, int $value){
            return $user->permission->product == $value;
        });

        Gate::define('supplier-permission',function(User $user, int $value){
            return $user->permission->supplier == $value;
        });

        Gate::define('partner-permission',function(User $user, int $value){
            return $user->permission->partner == $value;
        });

        Gate::define('item-permission',function(User $user, int $value){
            return $user->permission->item == $value;
        });

        Gate::define('transaction-permission',function(User $user, int $value){
            return $user->permission->transaction == $value;
        });

        Gate::define('delivery-permission',function(User $user, int $value){
            return $user->permission->delivery == $value;
        });

        Gate::define('client-permission',function(User $user, int $value){
            return $user->permission->client == $value;
        });

        Gate::define('voucher-permission',function(User $user, int $value){
            return $user->permission->voucher == $value;
        });

        Gate::define('ledger-permission',function(User $user, int $value){
            return $user->permission->ledger == $value;
        });

        Gate::define('config-permission',function(User $user, int $value){
            return $user->permission->config == $value;
        });
        Gate::define('history-permission',function(User $user, int $value){
            return $user->permission->history == $value;
        });

        Gate::define('report-permission',function(User $user, int $value){
            return $user->permission->report == $value;
        });


        /// reads
        Gate::define('product-read',function(User $user){
            return $user->permission->product >= 2;
        });

        Gate::define('supplier-read',function(User $user){
            return $user->permission->supplier >= 2;
        });

        Gate::define('partner-read',function(User $user){
            return $user->permission->partner >= 2;
        });

        Gate::define('item-read',function(User $user){
            return $user->permission->item >= 2;
        });

        Gate::define('transaction-read',function(User $user){
            return $user->permission->transaction >= 2;
        });

        Gate::define('delivery-read',function(User $user){
            return $user->permission->delivery >= 2;
        });

        Gate::define('client-read',function(User $user){
            return $user->permission->client >= 2;
        });

        Gate::define('voucher-read',function(User $user){
            return $user->permission->voucher >= 2;
        });

        Gate::define('ledger-read',function(User $user){
            return $user->permission->ledger >= 2;
        });
        Gate::define('report-read',function(User $user){
            return $user->permission->report >= 2;
        });
        Gate::define('config-read',function(User $user){
            return $user->permission->config >= 2;
        });
        Gate::define('history-read',function(User $user){
            return $user->permission->history >= 2;
        });
    }
}
