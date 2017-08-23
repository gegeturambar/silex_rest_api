<?php
/**
 * Created by PhpStorm.
 * User: jsimonney
 * Date: 22/08/2017
 * Time: 15:50
 */

namespace Repository;


use Doctrine\DBAL\Connection;
use Entity\AbstractEntity;

class Repository
{
    protected $_db;

    protected $_entityClass;

    protected $_tableName = null;

    protected $_uniqueIdx = null;

    protected function getTableName()
    {
        if(is_null($this->_tableName))
            $this->_tableName = call_user_func($this->_entityClass.'::getTableName');
        return $this->_tableName;
    }

    protected function getUniquesIndex()
    {
        if(is_null($this->_uniqueIdx)){
            $this->_uniqueIdx =  call_user_func($this->_entityClass.'::getUniquesIndex');
        }
        return $this->_uniqueIdx;
    }

    protected function checkBeforeSave(AbstractEntity $entity)
    {
        // check uniqueIndex
        foreach($this->getUniquesIndex() as $index){
            $sql = "SELECT * FROM ". $this->getTableName()." WHERE ";
            $data = array();
            foreach($index as $idxPart){
                $sql .= " $idxPart = ? AND ";
                $data[] = $entity->getData()[$idxPart];
            }
            $sql = substr($sql,0,-4);
            $row = $this->_db->fetchAssoc($sql, $data);
            if($row)
                return false;
        }
        return true;
    }

    public function __construct(Connection $db, string $entityClass)
    {
        if(!in_array('Entity\AbstractEntity',class_parents($entityClass)))
            throw new \Exception("The class $entityClass must be child of AbstractEntity");
        $this->_db = $db;
        if(!class_exists($entityClass))
            throw new \Exception("The class $entityClass does not exists");
        $this->_entityClass = $entityClass;
    }

    public function findAll()
    {
        $sql = "SELECT * FROM ". $this->getTableName();
        $result = $this->_db->fetchAll($sql);

        $entities = array();
        foreach($result as $row){
            $id = $row['id'];
            $entities[$id] = new $this->_entityClass($row);
        }
        return $entities;
    }

    public function find($id)
    {
        $sql = "SELECT * FROM ".$this->getTableName()." WHERE id = ?";
        $row = $this->_db->fetchAssoc($sql, array($id));

        if($row){
            return new $this->_entityClass($row);
        }else{
            return null;
        }
    }

    public function save(AbstractEntity $entity)
    {
        $data = $entity->getData();

        $this->checkBeforeSave($entity);

        if($entity->getId()){
            $this->_db->update($this->getTableName(),$data,array('id'  =>  $entity->getId()));
        }else{
            $this->_db->insert($this->getTableName(),$data);
            $id = $this->_db->lastInsertId();
            $entity->setId($id);
        }
    }

    public function delete($id){
        $this->_db->delete($this->getTableName(),array('id'  =>  $id ) );
    }

}