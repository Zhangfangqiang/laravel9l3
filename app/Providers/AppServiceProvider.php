<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (app()->isLocal()) { #判断是否是本地环境
            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
	{
		\App\Models\User::observe(\App\Observers\UserObserver::class);
		\App\Models\Reply::observe(\App\Observers\ReplyObserver::class);
		\App\Models\Topic::observe(\App\Observers\TopicObserver::class);
		\App\Models\Link::observe(\App\Observers\LinkObserver::class);
        \Illuminate\Pagination\Paginator::useBootstrap();                   #翻页使用Bootstrap
        JsonResource::withoutWrapping();                                    #去除json外面的{}
    }
}
