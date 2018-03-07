<?php
/**
 * Created by PhpStorm.
 * Version: jsimonney
 * Date: 23/08/2017
 * Time: 10:19
 */

namespace Controller;


use Entity\Version;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class VersionController
{
    public function indexAction(Application $app, Request $request)
    {
        $versions = $app['repository.version']->findAll();
        $responseData = array();
        foreach($versions as $version){
            $responseData[] = array(
                'id'    =>  $version->getId(),
                'numero' =>  $version->getNumero(),
                'dateCreated'   =>  $version->getDateCreated()
            );
        }

        return $app->json($responseData);
    }


    public function findAction(Application $app,Request $request,$id)
    {
        $version = $app['repository.version']->find($id);
        if(!isset($version)){
            $app->abort(404, 'Version does not exists');
        }

        $responseData = array(
            'id'    =>  $version->getId(),
            'numero' =>  $version->getNumero(),
            'dateCreated'   =>  $version->getDateCreated()
        );
        return $app->json($responseData);
    }

    public function createAction(Application $app,Request $request)
    {
        $requiredParams = array('numero');
        foreach($requiredParams as $requiredParam) {
            if (!$request->request->has($requiredParam)) {
                return $app->json("Missing parameter: $requiredParam",400);
            }
        }

        $version = new Version();
        $version->setNumero($request->request->get('numero'));
        $dateCreated = $request->request->get('dateCreated');
        $d = new \DateTime();
        $dateCreated = isset($dateCreated) ? $dateCreated : $d->format(Version::getFormat());
        $version->setDateCreated($dateCreated);
        $app['repository.version']->save($version);

        $responseData = $version->getData();

        return $app->json($responseData,201);
    }

    public function deleteAction(Application $app,Request $request,$id)
    {
        $app['repository.version']->delete($id);

        return $app->json('No content', 204);
    }

    public function updateAction(Application $app, Request $request,$id)
    {
        /** @var Version $version */
        $version   =   $app['repository.version']->find($id);

        $version->setNumero($request->request->get('numero'));
        $dateCreated = $request->request->get('dateCreated');
        $d = new \DateTime();

        $dateCreated = isset($dateCreated) ? $dateCreated : $d->format(Version::getFormat());
        $version->setDateCreated($dateCreated);

        $app['repository.version']->save($version);

        $responseData = $version->getData();

        return $app->json($responseData,202);
    }
}