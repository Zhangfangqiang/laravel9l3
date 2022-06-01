<?php

namespace App\Providers;

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use mysql_xdevapi\Exception;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(User $user, Link $link)
    {
        try {
            view::share(['links' => $link->getAllCached()]);
            view::share(['active_users' => $user->getActiveUsers()]);
        } catch (\Exception $e) {

        }
    }
}
