<?php

namespace Esraaashour\Slug;

use Illuminate\Support\ServiceProvider;

class SlugServiceProvider extends ServiceProvider{
    public function boot(){
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    public function register(){

    }
}
