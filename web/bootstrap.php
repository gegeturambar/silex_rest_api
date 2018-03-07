<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application();

$app['settings'] = Yaml::parse(file_get_contents(__DIR__."/../app/config/config.yml"));

$settings_dev = Yaml::parse(file_get_contents(__DIR__."/../app/config/config_dev.yml"));
if($settings_dev)
    $app['settings'] = array_merge($app['settings'], $settings_dev);

$app['debug'] = true;

require __DIR__.'/../app/app.php';
require __DIR__.'/../app/routes.php';



$app->run();
