<?php

namespace srag\DIC\SrJiraProcessHelper\DIC;

use ILIAS\DI\Container;
use srag\DIC\SrJiraProcessHelper\Database\DatabaseDetector;
use srag\DIC\SrJiraProcessHelper\Database\DatabaseInterface;

/**
 * Class AbstractDIC
 *
 * @package srag\DIC\SrJiraProcessHelper\DIC
 */
abstract class AbstractDIC implements DICInterface
{

    /**
     * @var Container
     */
    protected $dic;


    /**
     * @inheritDoc
     */
    public function __construct(Container &$dic)
    {
        $this->dic = &$dic;
    }


    /**
     * @inheritDoc
     */
    public function database() : DatabaseInterface
    {
        return DatabaseDetector::getInstance($this->databaseCore());
    }
}
