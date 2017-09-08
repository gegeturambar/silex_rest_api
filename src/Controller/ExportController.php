<?php
/**
 * Created by PhpStorm.
 * Traduction: jsimonney
 * Date: 23/08/2017
 * Time: 10:19
 */

namespace Controller;


use Entity\Langue;
use Entity\Traduction;
use Entity\Version;
use Repository\Repository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use duncan3dc\Excel;

class ExportController
{
    public function indexAction(Application $app, Request $request)
    {
        // get version
        $versions = $app['repository.version']->findAll(array("dateCreated"),"DESC",1);
        $lastVersion = array_pop($versions);
        $responseData = array("version"=> $lastVersion->getNumero());

        $langues = $app['repository.langue']->findAll();

        foreach($langues as $langue){
            $traductions = $app['repository.traduction']->findBy(array('langueId'=>$langue->getId() ) );
            $responseData[$langue->getCode()] = array(
                "label"         => $langue->getName(),
                "code_store"    =>  $langue->getCode(),
                "keys"          =>  array()
                );

            if(count($traductions)){
                foreach ($traductions as $trad){
                    $responseData[$langue->getCode()]["keys"][$trad->getTag()] = $trad->getValue();
                }
            }
        }

        return $app->json($responseData);
    }
}