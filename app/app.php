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

ErrorHandler::register();
ExceptionHandler::register();

/* @var Silex\Application $app */
$app->register(new Silex\Provider\DoctrineServiceProvider());

$app['dao.user'] = $app