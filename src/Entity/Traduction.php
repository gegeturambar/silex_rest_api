<?php

namespace Entity;

class Traduction extends AbstractEntity
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $tag;

    /**
     * @var integer
     */
    private $langueId;

    /**
     * @return int
     */
    public function getLangueId()
    {
        return $this->langueId;
    }

    /**
     * @param int $langueId
     */
    public function setLangueId($langueId)
    {
        $this->langueId = $langueId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param value $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @var value
     */
    private $value;

    protected static $_properties = null;

    protected static $_tablename = "traduction";

}