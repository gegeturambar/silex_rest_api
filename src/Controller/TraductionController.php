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

class TraductionController
{
    public function indexAction(Application $app, Request $request,$limit=null,$offset=null)
    {
	if(!is_null($limit)){
		$limit = (int)$limit;
		if(!is_null($offset))
			$offset = (int)$offset;
	}

        $traductions = $app['repository.traduction']->findAll(array(),"ASC",$limit,$offset);
        $responseData = array();
        /** @var Traduction $traduction */
        foreach($traductions as $traduction){
            $responseData[] = array(
                'id'    =>  $traduction->getId(),
                'tag'   =>  $traduction->getTag(),
                'lang'  =>  $app['repository.langue']->find($traduction->getLangueId())->getCode()  ,
                'value' =>  $traduction->getValue()

            );
        }

        return $app->json($responseData);
    }


    public function findAction(Application $app,Request $request,$id)
    {
        $traduction = $app['repository.traduction']->find($id);
        if(!isset($traduction)){
            $app->abort(404, 'Traduction does not exists');
        }

        $responseData = array(
            'id'    =>  $traduction->getId(),
            'tag'   =>  $traduction->getTag(),
            'lang'  =>  $app['repository.langue']->find($traduction->getLangueId())->getCode()  ,
            'value' =>  $traduction->getValue()
        );
        return $app->json($responseData);
    }

    public function findByLangAction(Application $app, Request $request,$lang , $tag = null)
    {
        if(!is_integer($lang)){
            $langId = $app['repository.langue']->findBy(array("code" => $lang, "name"   =>  $lang ), false );
            if(!count($langId)){
                throw new \Exception("no langue found with name or code $lang");
            }
            $langId = array_pop($langId);
            $langId = $langId->getId();
        }else{
            $langId = $lang;
        }

        $filters = array('langueId'=>$langId );
        if(!is_null($tag)){
            $filters["tag"] = $tag;
        }

        $traductions = $app['repository.traduction']->findBy($filters );
        if(!isset($traductions)){
            $app->abort(404, 'Traductions do not exist');
        }

        $responseData = array();
        /** @var Traduction $traduction */
        foreach($traductions as $traduction){
            $responseData[] = array(
                'id'    =>  $traduction->getId(),
                'tag'   =>  $traduction->getTag(),
                'lang'  =>  $app['repository.langue']->find($traduction->getLangueId())->getCode()  ,
                'value' =>  $traduction->getValue()

            );
        }

        return $app->json($responseData);
    }

    public function findByTagAction(Application $app, Request $request,$tag , $lang = null)
    {


        $filters = array('tag'=>$tag );
        if(!is_null($lang)){
            if(!is_integer($lang)){
                $langId = $app['repository.langue']->findBy(array("code" => $lang, "name"   =>  $lang ), false );
                if(count($langId)){
                    $langId = array_pop($langId);
                    $langId = $langId->getId();
                    $filters["langueId"] = $langId;
                }
            }else{
                $langId = $lang;
                $filters["langId"] = $langId;
            }
        }

        $traductions = $app['repository.traduction']->findBy($filters );
        if(!isset($traductions)){
            $app->abort(404, 'Traductions do not exist');
        }

        $responseData = array();
        /** @var Traduction $traduction */
        foreach($traductions as $traduction){
            $responseData[] = array(
                'id'    =>  $traduction->getId(),
                'tag'   =>  $traduction->getTag(),
                'lang'  =>  $app['repository.langue']->find($traduction->getLangueId())->getCode()  ,
                'value' =>  $traduction->getValue()

            );
        }

        return $app->json($responseData);
    }

    public function createAction(Application $app,Request $request)
    {
        if ($request->request->get("langue")) {
            $language = $app['repository.langue']->findBy(array('name' => $request->request->get("langue")));
            if (!count($language))
                throw new \Exception('langue is not valid');
            $request->request->set('langueId', array_pop($language)->getId());
        }

        $requiredParams = array('tag', "value", "langueId");
        foreach ($requiredParams as $requiredParam) {
            if (!$request->request->has($requiredParam)) {
                return $app->json("Missing parameter: $requiredParam", 400);
            }
        }

        foreach (Traduction::getProperties() as $fieldName) {
            if($fieldName == 'id')
                continue;
            $data[$fieldName] = $request->request->get($fieldName);
        }
        $responseData = $this->createTraduction($app,$data);
        return $app->json($responseData,201);

    }


    public function importAction(Application $app,Request $request)
    {

        // get path from settings, then read file, truncate table and import !
        $path = $app['settings']['file_location'];

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
                                    'id' => $traduction->getId(),
                                    'tag' => $traduction->getTag(),
                                    'lang' => $app['repository.langue']->find($traduction->getLangueId())->getCode(),
                                    'value' => $traduction->getValue()
                                );
                            }
                        }
                        $colCount++;
                    }
                    $rowCount++;
                }
            }


            // maj version
            /** @var Repository $versRep */
            $versRep = $app['repository.version'];
            $versions = $versRep->findAll(array("dateCreated"), "DESC");
            $lastnum = array_pop($versions)->getNumero();
            $nums = str_replace(".", "", $lastnum);
            $nums++;
            $nums = str_split($nums);
            $lastnum = implode(".", $nums);
            $lastVersion = new Version();
            $lastVersion->setNumero($lastnum);
            $d = new \DateTime();
            $dateCreated = isset($dateCreated) ? $dateCreated : $d->format(Version::getFormat());
            $lastVersion->setDateCreated($dateCreated);
            $versRep->save($lastVersion);
        }


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

        $value = $request->request->get('value');
        if(isset($value))
            $traduction->setValue($value);

        $tag = $request->request->get('tag');
        if(isset($tag))
            $traduction->setTag($tag);

        $tag = $request->request->get('langue');
        if(isset($langue))
            $traduction->setLangueId($langue);

        $app['repository.traduction']->save($traduction);

        $responseData = $traduction->getData();

        return $app->json($responseData,202);
    }
}
