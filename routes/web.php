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

    $router->group(['prefix' => 'client'], function () use ($router)
    {
        $router->get('/search', [
            'uses' => 'ClientController@search'
        ]);

        $router->put('/{client}/update', [
            'uses' => 'ClientController@update'
        ]);

        $router->get('/{client}/detail', [
            'uses' => 'ClientController@detail'
        ]);

        $router->delete('/{client}/delete', [
            'uses' => 'ClientController@delete'
        ]);

        $router->post('/store', [
            'uses' => 'ClientController@store'
        ]);
    });
    /*client*/

    $router->get('/projects/{client}', [
        'uses' => 'ProjectController@index'
    ]);

    $router->get('/project/all', [
        'uses' => 'ProjectController@getAll'
    ]);

    $router->group(['prefix' => 'project'], function () use ($router)
    {
        $router->get('/status', [
            'uses' => 'ProjectController@projectStatus'
        ]);

        $router->get('/deadline', [
            'uses' => 'ProjectController@projectNearDeadline'
        ]);

        $router->get('/income', [
            'uses' => 'ProjectController@incomeByProject'
        ]);

        $router->get('/bestfive', [
            'uses' => 'ProjectController@bestFiveProject'
        ]);

        $router->get('/search', [
            'uses' => 'ProjectController@search'
        ]);

        $router->get('/trash/search', [
            'uses' => 'ProjectController@trashSearch'
        ]);

        $router->put('/{project}/update', [
            'uses' => 'ProjectController@update'
        ]);

        $router->get('/{project}/detail', [
            'uses' => 'ProjectController@detail'
        ]);

        $router->delete('/{project}/delete', [
            'uses' => 'ProjectController@delete'
        ]);

        $router->post('/store', [
            'uses' => 'ProjectController@store'
        ]);

        $router->put('/{project}/project_status/{project_status}', [
            'uses' => 'ProjectController@updateProjectStatus'
        ]);

        $router->put('/{project}/payment_status/{payment_status}', [
            'uses' => 'ProjectController@updatePaymentStatus'
        ]);

        $router->put('/{project}/payment_method/{payment_method}', [
            'uses' => 'ProjectController@updatePaymentMethod'
        ]);

        $router->get('/notification', [
            'uses' => 'ProjectController@reminder'
        ]);
    });


/*project*/

    $router->get('/project_statuses', [
        'uses' => 'ProjectStatusController@index'
    ]);

    $router->get('/project_statuses/all', [
        'uses' => 'ProjectStatusController@getAll'
    ]);

    $router->group(['prefix' => 'project_status'], function () use ($router)
    {
        $router->get('/search', [
            'uses' => 'ProjectStatusController@search'
        ]);

        $router->put('/{project_status}/update', [
            'uses' => 'ProjectStatusController@update'
        ]);

        $router->get('/{project_status}/detail', [
            'uses' => 'ProjectStatusController@detail'
        ]);

        $router->delete('/{project_status}/delete', [
            'uses' => 'ProjectStatusController@delete'
        ]);

        $router->post('/store', [
            'uses' => 'ProjectStatusController@store'
        ]);
    });

/*project status*/

    $router->get('/payment_statuses', [
        'uses' => 'PaymentStatusController@index'
    ]);

    $router->get('/payment_statuses/all', [
        'uses' => 'PaymentStatusController@getAll'
    ]);

    $router->group(['prefix' => 'payment_status'], function () use ($router)
    {
        $router->get('/search', [
            'uses' => 'PaymentStatusController@search'
        ]);

        $router->put('/{payment_status}/update', [
            'uses' => 'PaymentStatusController@update'
        ]);

        $router->get('/{payment_status}/detail', [
            'uses' => 'PaymentStatusController@detail'
        ]);

        $router->delete('/{payment_status}/delete', [
            'uses' => 'PaymentStatusController@delete'
        ]);

        $router->post('/store', [
            'uses' => 'PaymentStatusController@store'
        ]);
    });

    /*payment status*/
    $router->get('/payment_methods', [
        'uses' => 'PaymentMethodController@index'
    ]);

    $router->get('/payment_methods/all', [
        'uses' => 'PaymentMethodController@getAll'
    ]);

    $router->group(['prefix' => 'payment_method'], function () use ($router)
    {
        $router->get('/search', [
            'uses' => 'PaymentMethodController@search'
        ]);

        $router->put('/{payment_method}/update', [
            'uses' => 'PaymentMethodController@update'
        ]);

        $router->get('/{payment_method}/detail', [
            'uses' => 'PaymentMethodController@detail'
        ]);

        $router->delete('/{payment_method}/delete', [
            'uses' => 'PaymentMethodController@delete'
        ]);

        $router->post('/store', [
            'uses' => 'PaymentMethodController@store'
        ]);
    });

/*Profile */
    $router->group(['prefix' => 'profile'], function () use ($router)
    {
        $router->get('/{user}/detail', [
            'uses' => 'ProfileController@index'
        ]);

        $router->put('/{user}/update', [
            'uses' => 'ProfileController@update'
        ]);
    });

    $router->get('/payments', [
        'uses' => 'PaymentController@index'
    ]);

    $router->get('/payments/all', [
        'uses' => 'PaymentController@getAll'
    ]);


    $router->group(['prefix' => 'payment'], function () use ($router)
    {
        $router->get('/search', [
            'uses' => 'PaymentController@search'
        ]);

        $router->put('/{payment}/update', [
            'uses' => 'PaymentController@update'
        ]);

        $router->get('/{payment}/detail', [
            'uses' => 'PaymentController@detail'
        ]);

        $router->delete('/{payment}/delete', [
            'uses' => 'PaymentController@delete'
        ]);

        $router->post('/store', [
            'uses' => 'PaymentController@store'
        ]);
    });
});
