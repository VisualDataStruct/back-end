<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/**
 * @var $router \Laravel\Lumen\Routing\Router
 */
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([
    'prefix' => 'auth',
    'namespace' => 'Auth'
], function () use ($router) {
    $router->post('/login', 'Login@handle');

    $router->put('/security/{id}', 'ChangePassword@handle');

    $router->post('/forget', 'ForgetPassword@handle');

    $router->post('/reset', 'ResetPassword@handle');
});

$router->group([
    'prefix' => 'user',
    'namespace' => 'User',
], function () use ($router) {
    $router->post('/add', 'Add@handle');

    $router->get('/', 'List@handle');

    $router->get('/{user_id}', 'Detail@handle');

    $router->put('/{user_id}', 'Change@handle');
});

$router->group([
    'prefix' => 'classification',
    'namespace' => 'Classification',
], function () use ($router) {
    $router->get('/', 'List@handle');

    $router->post('/', 'Add@handle');

    $router->put('/{id}', 'Change@handle');

    $router->delete('/{id}', 'Delete@handle');

    $router->post('/{id}', 'Restore@handle');
});

$router->group([
    'prefix' => 'classification/{id}/algorithm',
    'namespace' => 'Algorithm',
], function () use ($router) {
    $router->get('/', 'List@handle');

    $router->get('/{id}', 'Detail@handle');

    $router->post('/', 'Add@handle');

    $router->put('/{id}/verify', 'Verify@handle');

    $router->put('/{id}', 'Change@handle');

    $router->delete('/{id}', 'Delete@handle');

    $router->post('/{id}', 'Restore@handle');

    $router->post('/{id}/problem', 'AddProblem@handle');

    $router->delete('/{id}/problem', 'DeleteProblem@handle');
});