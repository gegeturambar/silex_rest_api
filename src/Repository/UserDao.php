<?php

namespace Repository;

use Doctrine\DBAL\Connection;

class UserDao
{
    private $db;

    private $tablename = 'user';

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    protected function getDb()
    {
        return $this->db;
    }

    protected function buildDomainObject($row)
    {
        $user = new User();
        $user->setId($row['id']);
        $user->setFirstname($row['firstname']);
        $user->setLastname($row['lastname']);
        return $user;
    }

    public function findAll()
    {
        $sql = "SELECT * FROM USER ";
        $result = $this->getDb()->fetchAll($sql);

        $entities = array();
        foreach($result as $row){
            $id = $row['id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    public function find($id)
    {
        $sql = "SELECT * FROM $this->tablename WHERE id = ?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if($row){
            return $this->buildDomainObject($row);
        }else{
            return false;
        }
    }

    public function save(User $user)
    {
        $userData = array(
            'firstname' =>  $user->getFirstname(),
            'lastname'  =>  $user->getLastname()
        );

        if($user->getId()){
            $this->getDb()->update($this->tablename,$userData,array('id'  =>  $user->getId()));
        }else{
            $this->getDb()->insert($this->tablename,$userData);
            $id = $this->getDb()->lastInsertId();
            $user->setId($id);
        }
    }

    public function delete($id){
        $this->getDb()->delete($this->tablename,array('id'  =>  $id ) );
    }

}