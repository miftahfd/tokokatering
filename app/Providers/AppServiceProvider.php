<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Models\TicketMaintenancePacket;
use App\Models\User;
use App\Models\UserWeb;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        setlocale(LC_ALL, 'IND');

        view()->composer('*',function($view) {
            $user_logged = auth()->user();
            
            if($user_logged) {
                $view->with('user_logged', $user_logged);
                $view->with('user_logged_roles', $user_logged->roles);
                $view->with('user_logged_active_role', $user_logged->activeRole);
            }
        });
    }
}
