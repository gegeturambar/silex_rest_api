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

    public function importAction(Application $app,Request $request)
    {
        // get path from settings, then read file, truncate table and import !
        $path = __DIR__.'\\'.$app['settings']['file_location'];

        $responseData = array();

        if(file_exists($path)) {
            $keys = array();

            $ct = \duncan3dc\Phpexcel\Excel::read($path);
            /** @var Repository $tradRep */
            $tradRep = $app['repository.traduction'];

            /** @var Repository $langRep */
            $langRep = $app['repository.langue'];
            $tradRep->truncate();
            foreach ($ct as $tab) {
                $rowCount = 0;
                foreach ($tab as $row) {
                    $colCount = 0;
                    $tag = null;
                    foreach ($row as $col) {
                        if ($rowCount === 0) {
                            $keys[] = $col;
                        } else {
                            // insert into table
                            if ($keys[$colCount] === 'CLES') {
                                $tag = $col;
                            } else {
                                if (!$col)
                                    continue;
                                $lang = $keys[$colCount];
                                $traduction = new Traduction();

                                /** @var Langue $langue */
                                $langue = $langRep->findBy(array('code' => $keys[$colCount]));
                                if (!count($langue))
                                    continue;
                                $langue = array_pop($langue);
                                $traduction->setLangueId($langue->getId());
                                $traduction->setTag($tag);
                                $traduction->setValue($col);
                                $tradRep->save($traduction);

                                $responseData[] = array(
                                    'id'    =>  $traduction->getId(),
                                    'tag'   =>  $traduction->getTag(),
                                    'lang'  =>  $app['repository.langue']->find($traduction->getLangueId() )->getCode()  ,
                                    'value' =>  $traduction->getValue()
                                );
                            }
                        }
                        $colCount++;
                    }
                    $rowCount++;
                }
            }
        }

        // maj version
        /** @var Repository $versRep */
        $versRep = $app['repository.version'];
        $versions = $versRep->findAll(array("dateCreated"),"DESC");
        $lastnum = array_pop($versions)->getNumero();
        $nums = str_replace(".","",$lastnum);
        $nums++;
        $nums = str_split($nums);
        $lastnum = implode(".",$nums);
        $lastVersion = new Version();
        $lastVersion->setNumero($lastnum);
        $d = new \DateTime();
        $dateCreated = isset($dateCreated) ? $dateCreated : $d->format( Version::getFormat() );
        $lastVersion->setDateCreated($dateCreated);
        $versRep->save($lastVersion);


        return $app->json($responseData);
    }

    public function createTraduction($app,$data){
        $traduction = new Traduction();
        foreach (Traduction::getProperties() as $fieldName) {
            if(array_key_exists($fieldName,$data)){
                $fctionName = "set".ucfirst($fieldName);
                if(method_exists($traduction,$fctionName))
                    $traduction->$fctionName($data[$fieldName]);
            }
        }
        $app['repository.traduction']->save($traduction);
        return $traduction->getData();
    }

    public function deleteAction(Application $app,Request $request,$id)
    {
        $app['repository.traduction']->delete($id);

        return $app->json('No content', 204);
    }

    public function updateAction(Application $app, Request $request,$id)
    {
        /** @var Traduction $traduction */
        $traduction   =   $app['repository.traduction']->find($id);

        $traduction->setName($request->request->get('name'));

        $app['repository.traduction']->save($traduction);

        $responseData = $traduction->getData();

        return $app->json($responseData,202);
    }
}