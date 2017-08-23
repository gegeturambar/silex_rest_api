<?php

$app->get('/api/users','\Controller\UserController::indexAction')->bind('api_users');

/*
$app->get('/api/users',function () use ($app){

    $users = $app['repository.user']->findAll();
    $responseData = array();
    foreach($users as $user){
        $responseData[] = array(
            'id'    =>  $user->getId(),
            'firstname' =>  $user->getFirstname(),
            'lastname' =>  $user->getLastname()
        );
    }

    return $app->json($responseData);
})->bind('api_users');
*/

$app->get('/api/user/{id}', '\Controller\UserController::findAction')->bind('api_user');

$app->post('/api/user/create', '\Controller\UserController::createAction')->bind('api_user_add');

$app->delete('api/user/delete/{id}', '\Controller\UserController::deleteAction')->bind('api_user_delete');

$app->put('/api/user/update/{id}', '\Controller\UserController::updateAction')->bind('api_user_update');
