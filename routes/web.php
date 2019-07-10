<?php
$router->get('/', function () use ($router) {
    function generateRandomString($length = 120)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
    return generateRandomString();
});


$router->post('/auth/login',[
    'uses' => 'AuthController@login'
]);
$router->post('/auth/register',[
    'uses' => 'AuthController@register'
]);

$router->get('/auth/login/{user}/{verification_code}',[
    'uses' => 'AuthController@activatingAccount'
]);

$router->group(['middleware' => 'auth:api', 'cors'], function($router)
{
    $router->get('/test', function() {
        return response()->json([
            'message' => 'Hello World!',
        ]);
    });
    $router->delete('/auth/logout',[
        'uses' => 'AuthController@logout'
    ]);

    $router->get('/clients', [
        'uses' => 'ClientController@index'
    ]);

    $router->get('/clients/all', [
        'uses' => 'ClientController@getAll'
    ]);

    $router->get('/client/search', [
        'uses' => 'ClientController@search'
    ]);

    $router->put('/client/{client}/update', [
        'uses' => 'ClientController@update'
    ]);

    $router->get('/client/{client}/detail', [
        'uses' => 'ClientController@detail'
    ]);

    $router->delete('/client/{client}/delete', [
        'uses' => 'ClientController@delete'
    ]);

    $router->post('/client/store', [
        'uses' => 'ClientController@store'
    ]);

    /*client*/

    $router->get('/projects/{client}', [
        'uses' => 'ProjectController@index'
    ]);

    $router->get('/project/all', [
        'uses' => 'ProjectController@getAll'
    ]);

    $router->get('/project/search', [
        'uses' => 'ProjectController@search'
    ]);

    $router->put('/project/{project}/update', [
        'uses' => 'ProjectController@update'
    ]);

    $router->get('/project/{project}/detail', [
        'uses' => 'ProjectController@detail'
    ]);

    $router->delete('/project/{project}/delete', [
        'uses' => 'ProjectController@delete'
    ]);

    $router->post('/project/store', [
        'uses' => 'ProjectController@store'
    ]);

    $router->put('/project/{project}/project_status/{project_status}', [
        'uses' => 'ProjectController@updateProjectStatus'
    ]);

    $router->put('/project/{project}/payment_status/{payment_status}', [
        'uses' => 'ProjectController@updatePaymentStatus'
    ]);

    $router->put('/project/{project}/payment_method/{payment_method}', [
        'uses' => 'ProjectController@updatePaymentMethod'
    ]);

    $router->get('/project/notification', [
        'uses' => 'ProjectController@reminder'
    ]);


/*project*/

    $router->get('/project_statuses', [
        'uses' => 'ProjectStatusController@index'
    ]);

    $router->get('/project_statuses/all', [
        'uses' => 'ProjectStatusController@getAll'
    ]);

    $router->get('/project_status/search', [
        'uses' => 'ProjectStatusController@search'
    ]);

    $router->put('/project_status/{project_status}/update', [
        'uses' => 'ProjectStatusController@update'
    ]);

    $router->get('/project_status/{project_status}/detail', [
        'uses' => 'ProjectStatusController@detail'
    ]);

    $router->delete('/project_status/{project_status}/delete', [
        'uses' => 'ProjectStatusController@delete'
    ]);

    $router->post('/project_status/store', [
        'uses' => 'ProjectStatusController@store'
    ]);

/*project status*/

    $router->get('/payment_statuses', [
        'uses' => 'PaymentStatusController@index'
    ]);

    $router->get('/payment_statuses/all', [
        'uses' => 'PaymentStatusController@getAll'
    ]);

    $router->get('/payment_status/search', [
        'uses' => 'PaymentStatusController@search'
    ]);

    $router->put('/payment_status/{payment_status}/update', [
        'uses' => 'PaymentStatusController@update'
    ]);

    $router->get('/payment_status/{payment_status}/detail', [
        'uses' => 'PaymentStatusController@detail'
    ]);

    $router->delete('/payment_status/{payment_status}/delete', [
        'uses' => 'PaymentStatusController@delete'
    ]);

    $router->post('/payment_status/store', [
        'uses' => 'PaymentStatusController@store'
    ]);

    /*payment status*/


    $router->get('/payment_methods', [
        'uses' => 'PaymentMethodController@index'
    ]);

    $router->get('/payment_methods/all', [
        'uses' => 'PaymentMethodController@getAll'
    ]);

    $router->get('/payment_method/search', [
        'uses' => 'PaymentMethodController@search'
    ]);

    $router->put('/payment_method/{payment_method}/update', [
        'uses' => 'PaymentMethodController@update'
    ]);

    $router->get('/payment_method/{payment_method}/detail', [
        'uses' => 'PaymentMethodController@detail'
    ]);

    $router->delete('/payment_method/{payment_method}/delete', [
        'uses' => 'PaymentMethodController@delete'
    ]);

    $router->post('/payment_method/store', [
        'uses' => 'PaymentMethodController@store'
    ]);

/*Profile */
    $router->get('/profile/{user}/detail', [
        'uses' => 'ProfileController@index'
    ]);

    $router->put('/profile/{user}/update', [
        'uses' => 'ProfileController@update'
    ]);
});
