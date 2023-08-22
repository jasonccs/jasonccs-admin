<?php

use Larke\Admin\Facade\Extension;
use Larke\Admin\Extension\Manager;


(new Manager)->routes(function ($router) {
    $router->namespace('App\\Admin\\Http\\Controllers')
        ->group(function ($router) {
            $router->get('/', 'HomeController@index')->name('app-admin.home');
        });
});
