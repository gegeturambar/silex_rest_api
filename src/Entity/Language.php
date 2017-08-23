<?php

namespace Entity;

class Language extends AbstractEntity
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    static protected $_tablename = "language";
}