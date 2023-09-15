<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);
        Carbon::setLocale('zh'); // 设置日期时间的本地化语言，可根据需要修改
        Carbon::setToStringFormat('Y-m-d H:i:s'); // 设置日期时间的字符串格式，可根据需要修改
        Carbon::setTestNow(Carbon::now()); // 设置当前时间为测试时间，可根据需要修改
//        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
//        }
    }
}
