<?php
/**
 * Created by PhpStorm.
 * Translation: jsimonney
 * Date: 23/08/2017
 * Time: 10:19
 */

namespace Controller;


use Entity\Translation;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class TranslationController
{
    public function indexAction(Application $app, Request $request)
    {
        $translations = $app['repository.translation']->findAll();
        $responseData = array();
        foreach($translations as $translation){
            $responseData[] = array(
                'id'    =>  $translation->getId(),
                'name' =>  $translation->getName()
            );
        }

        return $app->json($responseData);
    }


    public function findAction(Application $app,Request $request,$id)
    {
        $translation = $app['repository.translation']->find($id);
        if(!isset($translation)){
            $app->abort(404, 'Translation does not exists');
        }

        $responseData = array(
            'id'    =>  $translation->getId(),
            'firstname' =>  $translation->getName()
        );
        return $app->json($responseData);
    }

    public function createAction(Application $app,Request $request)
    {
        $requiredParams = array('name');
        foreach($requiredParams as $requiredParam) {
            if (!$request->request->has($requiredParam)) {
                return $app->json("Missing parameter: $requiredParam",400);
            }
        }

        $translation = new Translation();
        $translation->setName($request->request->get('name'));
        $app['repository.translation']->save($translation);

        $responseData = $translation->getData();

        return $app->json($responseData,201);
    }

    public function deleteAction(Application $app,Request $request,$id)
    {
        $app['repository.translation']->delete($id);

        return $app->json('No content', 204);
    }

    public function updateAction(Application $app, Request $request,$id)
    {
        /** @var Translation $translation */
        $translation   =   $app['repository.translation']->find($id);

        $translation->setName($request->request->get('name'));

        $app['repository.translation']->save($translation);

        $responseData = $translation->getData();

        return $app->json($responseData,202);
    }
}