<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('logout', 'AuthController@logout');
        $router->post('refresh', 'AuthController@refresh');
        $router->post('user-profile', 'AuthController@me');

        $router->get('get-report-bills', 'BillController@reportBills');
        $router->get('get-all-bills', 'BillController@getAllBills');
        $router->post('create-bill', 'BillController@createBill');
        $router->get('get-bill/{id}', 'BillController@getBill');
        $router->put('update-bill/{id}', 'BillController@updateBill');
        $router->delete('delete-bill/{id}', 'BillController@deleteBill');

        $router->get('get-all-categories', 'CategoryController@getAllCategories');
        $router->post('create-category', 'CategoryController@createCategory');
        $router->get('get-category/{id}', 'CategoryController@getCategory');
        $router->put('update-category/{id}', 'CategoryController@updateCategory');
        $router->delete('delete-category/{id}', 'CategoryController@deleteCategory');

        $router->get('get-all-notifications', 'BillController@getNotifications');
    });
});
