<?php
/**
 * Created by PhpStorm.
 * User: jsimonney
 * Date: 16/08/2017
 * Time: 11:36
 */

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

ErrorHandler::register();
ExceptionHandler::register();

/* @var Silex\Application $app */
$app->register(new Silex\Provider\DoctrineServiceProvider(),
    array(
        "db.options"=>    $app['settings']['database']
    )
);

/*
$app['dao.user'] = function ($app) {
    return new Model\UserDao($app['db']);
};
*/

$app['repository.language'] = function($app){
    return new \Repository\Repository($app['db'],\Entity\Language::class);
};

$app['repository.user'] = function($app){
    return new \Repository\Repository($app['db'],\Entity\User::class);
};

$app['repository.translation'] = function($app){
    return new \Repository\Repository($app['db'],\Entity\Translation::class);
};

$app['repository.version'] = function($app){
    return new \Repository\Repository($app['db'],\Entity\Version::class);
};


$app->before(function(Request $request){
    if( 0 === strpos($request->headers->get('Content-Type'), 'application/json')){
        $data = json_decode($request->getContent(),true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->after(function (Request $request, Response $response ){

});

$app->finish(function (Request $request, Response $response ){

});


