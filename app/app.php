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

// password encoder
$app['security.default_encoder'] = function ($app) {
	return $app['security.encoder.pbkdf2'];
};

$app['repository.langue'] = function($app){
    return new \Repository\Repository($app['db'],\Entity\Langue::class);
};

$app['repository.user'] = function($app){
    return new \Repository\Repository($app['db'],\Entity\User::class);
};

$app['app.username_password_authenticator'] = function($app){
    return new Security\UsernamePasswordAuthenticator($app['security.encoder_factory']);
};

$app['security.token_coder'] = function($app){
    return new Security\TokenCoder("somethingSecret");
};

$app['app.token_authenticator'] = function($app){
    return new Security\TokenAuthenticator($app['security.token_coder']);
};

$app['security.firewalls'] = array(

    'login_signup' => array(
	    'pattern' => "^.*[signup|login]$",
	    'methods' => ['POST'],
	    'anonymous' => true
    ),

    'main' => array(
	    'pattern' => "^.*",
	    'methods' => ['POST','PUT','DELETE'],
	    'guard' => array(
		    'authenticators' => array(
			    'app.token_authenticator'
		    ),
	    ),
	    'users' => function($app) {
		    return new \Security\UserProvider($app['repository.user']);
	    },
    ),
);

$app['security.role_hierarchy'] = array(
	'ROLE_ADMIN' => array('ROLE_USER'),
);

$app['security.access_rules'] = array(
	array('^/api/signup$', 'IS_AUTHENTICATED_ANONYMOUSLY',['POST']),
	array('^.*$', 'ROLE_ADMIN', ['POST','PUT','DELETE'] ),
	array('^.*login$', 'IS_AUTHENTICATED_ANONYMOUSLY',['POST']),
	array('^.*$', 'IS_AUTHENTICATED_ANONYMOUSLY',['GET'])
);

$app->register(new \Silex\Provider\SecurityServiceProvider());
		
$app['repository.traduction'] = function($app){
    return new \Repository\Repository($app['db'],\Entity\Traduction::class);
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

$app->after(function (Request $request, Response $response, $app ){
    $response->headers->set('version', $app['settings']['version']);
});

$app->finish(function (Request $request, Response $response ){
});


