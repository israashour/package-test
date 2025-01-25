<?php

namespace es\lightloglite;

use Illuminate\Support\ServiceProvider;

class LightLogLiteServiceProvider extends ServiceProvider {
    public function boot() {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    public function register() {

    }
}
