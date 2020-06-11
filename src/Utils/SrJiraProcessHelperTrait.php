<?php

namespace srag\Plugins\SrJiraProcessHelper\Utils;

use srag\Plugins\SrJiraProcessHelper\Repository;

/**
 * Trait SrJiraProcessHelperTrait
 *
 * @package srag\Plugins\SrJiraProcessHelper\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
