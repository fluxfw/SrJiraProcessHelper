<?php

namespace srag\Plugins\SrJiraProcessHelper\Utils;

use srag\Plugins\SrJiraProcessHelper\Repository;

/**
 * Trait SrJiraProcessHelperTrait
 *
 * @package srag\Plugins\SrJiraProcessHelper\Utils
 */
trait SrJiraProcessHelperTrait
{

    /**
     * @return Repository
     */
    protected static function srJiraProcessHelper() : Repository
    {
        return Repository::getInstance();
    }
}
