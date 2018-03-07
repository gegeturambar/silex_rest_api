<?php
/**
 * Created by PhpStorm.
 * User: jsimonney
 * Date: 23/08/2017
 * Time: 10:19
 */

namespace Controller;


use Entity\User;
use Security\TokenAuthenticator;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController
{

    public function indexAction(Application $app, Request $request)
    {
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
    }


    public function loginAction(Application $app, Request $request)
    {

        $requiredParams = array('email','password');
        foreach($requiredParams as $requiredParam) {
            if (!$request->request->has($requiredParam)) {
                return $app->json("Missing parameter: $requiredParam",400);
            }
        }
        $email = $request->request->get('email');

        /** @var User $user */
        $user   =   $app['repository.user']->findOneBy(array('email'=>$email));
        if(!$user){
            return $app->json('User does not exists',404);
        }
        $credentials = array('email'=>$email,'secret'=>$request->request->get('password'));
        //var_dump($credentials);die();

        if($app['app.username_password_authenticator']->checkCredentials($credentials,$user)) {
            $token = $app['security.token_coder']->encode([
                'username' => $user->getEmail()
            ]);
            return new JsonResponse([TokenAuthenticator::getTokenName() => $token]);
        }
    }

    public function signupAction(Application $app, Request $request)
    {
	    return $this->createAction($app,$request);
    }

    public function findAction(Application $app,Request $request,$id)
    {
        $user = $app['repository.user']->find($id);
        if(!isset($user)){
            $app->abort(404, 'User does not exists');
        }

        $responseData = array(
            'id'    =>  $user->getId(),
            'firstname' =>  $user->getFirstname(),
            'lastname'  =>  $user->getLastname()
        );
        return $app->json($responseData);
    }

    public function createAction(Application $app,Request $request)
    {
        $requiredParams = array('email','password');
        foreach($requiredParams as $requiredParam) {
            if (!$request->request->has($requiredParam)) {
                return $app->json("Missing parameter: $requiredParam",400);
            }
        }

        $user = new User();
        $user->setEmail($request->request->get('email'));
        $pwd = $request->request->get('password');
        $pwd = $app['security.default_encoder']->encodePassword($pwd,$user->getSalt());
        $user->setPassword($pwd);

        $optionnalParams = array('username','firstname','lastname');

        foreach($optionnalParams as $optionnalParam) {
            if ($request->request->has($optionnalParam)) {
		$fct = 'set'.ucfirst($optionnalParam);
		$user->$fct($request->request->get($optionnalParam));
            }
        }

	
        $app['repository.user']->save($user);

        $responseData = $user->getData();

        return $app->json($responseData,201);
    }

    public function deleteAction(Application $app,Request $request,$id)
    {
        $app['repository.user']->delete($id);

        return $app->json('No content', 204);
    }

    public function updateAction(Application $app, Request $request,$id)
    {
        /** @var User $user */
        $user   =   $app['repository.user']->find($id);

        $user->setFirstName($request->request->get('firstname'));
        $user->setLastName($request->request->get('lastname'));

        $app['repository.user']->save($user);

        $responseData = $user->getData();

        return $app->json($responseData,202);
    }
}
