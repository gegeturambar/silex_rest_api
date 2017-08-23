<?php

namespace Entity;

class Version extends AbstractEntity
{

    static protected $_tablename = "version";

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $number;

}