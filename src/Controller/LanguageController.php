<?php
/**
 * Created by PhpStorm.
 * Language: jsimonney
 * Date: 23/08/2017
 * Time: 10:19
 */

namespace Controller;


use Entity\Language;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class LanguageController
{
    public function indexAction(Application $app, Request $request)
    {
        $languages = $app['repository.language']->findAll();
        $responseData = array();
        foreach($languages as $language){
            $responseData[] = array(
                'id'    =>  $language->getId(),
                'name' =>  $language->getName()
            );
        }

        return $app->json($responseData);
    }


    public function findAction(Application $app,Request $request,$id)
    {
        $language = $app['repository.language']->find($id);
        if(!isset($language)){
            $app->abort(404, 'Language does not exists');
        }

        $responseData = array(
            'id'    =>  $language->getId(),
            'firstname' =>  $language->getName()
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

        $language = new Language();
        $language->setName($request->request->get('name'));
        $app['repository.language']->save($language);

        $responseData = $language->getData();

        return $app->json($responseData,201);
    }

    public function deleteAction(Application $app,Request $request,$id)
    {
        $app['repository.language']->delete($id);

        return $app->json('No content', 204);
    }

    public function updateAction(Application $app, Request $request,$id)
    {
        /** @var Language $language */
        $language   =   $app['repository.language']->find($id);

        $language->setName($request->request->get('name'));

        $app['repository.language']->save($language);

        $responseData = $language->getData();

        return $app->json($responseData,202);
    }
}