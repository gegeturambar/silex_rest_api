<?php

namespace Entity;

abstract class AbstractEntity{

    protected static $_tablename;

    public function __construct($row = null)
    {
        if(!is_null($row)){
            $this->populate($row);
        }
    }

    public static function getTableName()
    {
        return static::$_tablename;
    }

    public function populate($row){
        foreach($row as $name => $value){
            $setterName = "set".ucfirst($name);
            if(method_exists($this,$setterName)){
                $this->{$setterName}($value);
            }
        }
    }

    public function getData()
    {
        $r = new \ReflectionClass($this);
        $data = array();
        foreach($r->getProperties() as $p){
            if($p->isStatic())
                continue;
            $fctionName = 'get'.ucfirst($p->getName());
            if(method_exists($this,$fctionName)){
                $data[$p->getName()] = $this->$fctionName();
            }
        }
        return($data);
    }
}