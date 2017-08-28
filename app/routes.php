<?php

$app->get('/api/export','\Controller\ExportController::indexAction')->bind('api_export');

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


/** langues */

$app->get('/api/langues','\Controller\LangueController::indexAction')->bind('api_langues');

$app->get('/api/langue/{id}','\Controller\LangueController::findAction')->bind('api_langue');

$app->post('/api/langue/create','\Controller\LangueController::createAction')->bind('api_langue_add');

$app->delete('/api/langue/delete/{id}','\Controller\LangueController::deleteAction')->bind('api_langue_delete');

$app->put('/api/langue/update/{id}','\Controller\LangueController::updateAction')->bind('api_langue_update');


/** versions */

$app->get('/api/versions','\Controller\VersionController::indexAction')->bind('api_versions');

$app->get('/api/version/{id}','\Controller\VersionController::findAction')->bind('api_version');

$app->post('/api/version/create','\Controller\VersionController::createAction')->bind('api_version_add');

$app->delete('/api/version/delete/{id}','\Controller\VersionController::deleteAction')->bind('api_version_delete');

$app->put('/api/version/update/{id}','\Controller\VersionController::updateAction')->bind('api_version_update');


/** traductions */

$app->get('/api/traductions','\Controller\TraductionController::indexAction')->bind('api_traductions');

$app->get('/api/traductions/import','\Controller\TraductionController::importAction')->bind('api_traductions_import');

$app->get('/api/traduction/{id}','\Controller\TraductionController::findAction')->bind('api_traduction');


$app->get('/api/traductions/lang/{lang}','\Controller\TraductionController::findByLangAction')->bind('api_traductions_lang');
$app->get('/api/traductions/lang/{lang}/{tag}','\Controller\TraductionController::findByLangAction')->bind('api_traductions_lang_tag');

$app->get('/api/traductions/tag/{tag}','\Controller\TraductionController::findByTagAction')->bind('api_traductions_tag');
$app->get('/api/traductions/tag/{tag}/{lang}','\Controller\TraductionController::findByTagAction')->bind('api_traductions_tag_lang');


$app->post('/api/traduction/create','\Controller\TraductionController::createAction')->bind('api_traduction_add');

$app->delete('/api/traduction/delete/{id}','\Controller\TraductionController::deleteAction')->bind('api_traduction_delete');

$app->put('/api/traduction/update/{id}','\Controller\TraductionController::updateAction')->bind('api_traduction_update');


