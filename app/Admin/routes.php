<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('admin-users', AdminUserController::class);

    $router->resource('exchange-address-types', ExchangeAddressTypeController::class);

    $router->resource('smart-contracts', SmartContractController::class);

    $router->resource('env-settings', EnvSettingController::class);
});
