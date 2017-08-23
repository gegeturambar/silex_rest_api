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


/** languages */

$app->get('/api/languages','\Controller\LanguageController::indexAction')->bind('api_languages');

$app->get('/api/language/{id}','\Controller\LanguageController::findAction')->bind('api_language');

$app->post('/api/language/create','\Controller\LanguageController::createAction')->bind('api_language_add');

$app->delete('/api/language/delete/{id}','\Controller\LanguageController::deleteAction')->bind('api_language_delete');

$app->put('/api/language/update/{id}','\Controller\LanguageController::updateAction')->bind('api_language_update');


/** versions */

$app->get('/api/versions','\Controller\VersionController::indexAction')->bind('api_versions');

$app->get('/api/version/{id}','\Controller\VersionController::findAction')->bind('api_version');

$app->post('/api/version/create','\Controller\VersionController::createAction')->bind('api_version_add');

$app->delete('/api/version/delete/{id}','\Controller\VersionController::deleteAction')->bind('api_version_delete');

$app->put('/api/version/update/{id}','\Controller\VersionController::updateAction')->bind('api_version_update');


/** translations */

$app->get('/api/translations','\Controller\TranslationController::indexAction')->bind('api_translations');

$app->get('/api/translation/{id}','\Controller\TranslationController::findAction')->bind('api_translation');

$app->get('/api/translations/tag/{tag}/{lang}','\Controller\TranslationController::findByTagAction')->bind('api_translations_tag');

$app->get('/api/translations/lang/{lang}/{tag}','\Controller\TranslationController::findByLangAction')->bind('api_translations_lang');

$app->post('/api/translation/create','\Controller\TranslationController::createAction')->bind('api_translation_add');

$app->delete('/api/translation/delete/{id}','\Controller\TranslationController::deleteAction')->bind('api_translation_delete');

$app->put('/api/translation/update/{id}','\Controller\TranslationController::updateAction')->bind('api_translation_update');


