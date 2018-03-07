<?php

namespace Entity;

abstract class AbstractEntity{

    protected static $_tablename;

    protected static $_uniquesIndex = array();

    protected static $_properties = null;

    public static function getUniquesIndex()
    {
        return static::$_uniquesIndex;
    }

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

    public static function getProperties()
    {
        if(is_null(static::$_properties)) {
            $r = new \ReflectionClass(get_called_class());
            $data = array();
            foreach ($r->getProperties() as $p) {
                if ($p->isStatic())
                    continue;
                $fctionName = 'get' . ucfirst($p->getName());
                if ($r->hasMethod($fctionName)) {
                    static::$_properties[] = $p->getName();
                }
            }
        }
        return static::$_properties;
    }


    public function getData()
    {
        $data = array();
        foreach(static::getProperties() as $p){
            $fctionName = 'get'.ucfirst($p);
	    $value = $this->$fctionName();
	    $value = is_array($value) ? json_encode($value) : $value;
	    $data[$p] = $value;
        }
        return($data);
    }
}
