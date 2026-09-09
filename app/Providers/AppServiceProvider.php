<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Keep generated links on the same host/base path the user is browsing
        // (fixes WAMP /public vs php artisan serve APP_URL mismatches).
        if (! $this->app->runningInConsole() && ! $this->app->runningUnitTests()) {
            $request = request();
            $root = rtrim($request->getSchemeAndHttpHost().$request->getBaseUrl(), '/');
            if ($root !== '') {
                URL::forceRootUrl($root);
            }
        }
    }
}
