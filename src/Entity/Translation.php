<?php

namespace Entity;

class Translation extends AbstractEntity
{

    static protected $_tablename = "translation";

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
    private $languageId;

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
     * @return int
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * @param int $languageId
     */
    public function setLanguageId($languageId)
    {
        $this->languageId = $languageId;
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

}