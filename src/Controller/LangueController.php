<?php
/**
 * Created by PhpStorm.
 * Langue: jsimonney
 * Date: 23/08/2017
 * Time: 10:19
 */

namespace Controller;


use Entity\Langue;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class LangueController
{
    public function indexAction(Application $app, Request $request)
    {
        $langues = $app['repository.langue']->findAll();
        $responseData = array();
        foreach($langues as $langue){
            $responseData[] = array(
                'id'    =>  $langue->getId(),
                'name' =>  $langue->getName(),
                'code'  =>  $langue->getCode()
            );
        }

        return $app->json($responseData);
    }


    public function findAction(Application $app,Request $request,$id)
    {
        $langue = $app['repository.langue']->find($id);
        if(!isset($langue)){
            $app->abort(404, 'Langue does not exists');
        }

        $responseData = array(
            'id'    =>  $langue->getId(),
            'name' =>  $langue->getName(),
            'code'  =>  $langue->getCode()
        );
        return $app->json($responseData);
    }

    public function createAction(Application $app,Request $request)
    {
        $requiredParams = array('name','code');
        foreach($requiredParams as $requiredParam) {
            if (!$request->request->has($requiredParam)) {
                return $app->json("Missing parameter: $requiredParam",400);
            }
        }

        $langue = new Langue();
        $langue->setName($request->request->get('name'));
        $langue->setCode($request->request->get('code'));
        $app['repository.langue']->save($langue);

        $responseData = $langue->getData();

        return $app->json($responseData,201);
    }

    public function deleteAction(Application $app,Request $request,$id)
    {
        $app['repository.langue']->delete($id);

        return $app->json('No content', 204);
    }

    public function updateAction(Application $app, Request $request,$id)
    {
        /** @var Langue $langue */
        $langue   =   $app['repository.langue']->find($id);

        $attrs = array('name','code');
        foreach($attrs as $attr) {
            $val = $request->request->get($attr);
            if (isset($val)) {
                $fct = "set".ucfirst($attr);
                $langue->$fct($val);
            }
        }

        $app['repository.langue']->save($langue);

        $responseData = $langue->getData();

        return $app->json($responseData,202);
    }
}