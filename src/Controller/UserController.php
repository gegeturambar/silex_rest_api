<?php
/**
 * Created by PhpStorm.
 * User: jsimonney
 * Date: 23/08/2017
 * Time: 10:19
 */

namespace Controller;


use Entity\User;
use Silex\Application;
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
        $requiredParams = array('firstname','lastname');
        foreach($requiredParams as $requiredParam) {
            if (!$request->request->has($requiredParam)) {
                return $app->json("Missing parameter: $requiredParam",400);
            }
        }

        $user = new User();
        $user->setFirstname($request->request->get('firstname'));
        $user->setLastname($request->request->get('lastname'));
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