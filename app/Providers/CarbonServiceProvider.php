<?php

namespace App\Providers;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class CarbonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Carbon::setLocale('zh-CN');
        Carbon::setUtf8(true);
        Carbon::setToStringFormat('Y-m-d H:i:s');
        Carbon::useStrictMode(false);
        (new \Carbon\Carbon)->setTimezone('Asia/Shanghai');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // 设置默认语言为中文
        App::setLocale('zh-CN');
        // 设置 Carbon 的时区
        Carbon::setLocale('zh-CN');
        Carbon::setUtf8(true);
        Carbon::setUtf8(true);
        Carbon::setToStringFormat('Y-m-d H:i:s');
        Carbon::useStrictMode(false);
        (new \Carbon\Carbon)->setTimezone('Asia/Shanghai');
    }
}
