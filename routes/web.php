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

    $router->put('/{classification_id}', 'Change@handle');

    $router->delete('/{classification_id}', 'Delete@handle');

    $router->post('/{classification_id}', 'Restore@handle');
});

$router->group([
    'prefix' => 'classification/{classification_id}/algorithm',
    'namespace' => 'Algorithm',
], function () use ($router) {
    $router->get('/', 'List@handle');

    $router->get('/{algorithm_id}', 'Detail@handle');

    $router->post('/', 'Add@handle');

    $router->put('/{algorithm_id}/verify', 'Verify@handle');

    $router->put('/{algorithm_id}', 'Change@handle');

    $router->delete('/{algorithm_id}', 'Delete@handle');

    $router->post('/{algorithm_id}', 'Restore@handle');

    $router->post('/{algorithm_id}/problem', 'AddProblem@handle');

    $router->delete('/{algorithm_id}/problem', 'DeleteProblem@handle');
});