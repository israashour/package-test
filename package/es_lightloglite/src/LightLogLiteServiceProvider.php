<?php

namespace es\lightloglite;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class LightLogLiteServiceProvider extends ServiceProvider {
    public function boot() {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        Paginator::useBootstrap();
    }

    public function register() {

    }
}
