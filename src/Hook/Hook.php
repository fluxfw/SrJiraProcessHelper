<?php

namespace srag\Plugins\SrJiraProcessHelper\Hook;

use ilSrJiraProcessHelperPlugin;
use srag\DIC\SrJiraProcessHelper\DICTrait;
use srag\Plugins\SrJiraProcessHelper\Utils\SrJiraProcessHelperTrait;

/**
 * Class Hook
 *
 * @package srag\Plugins\SrJiraProcessHelper\Hook
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Hook
{

    use DICTrait;
    use SrJiraProcessHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrJiraProcessHelperPlugin::class;


    /**
     * Hook constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function handle()/*:void*/
    {
        $jira_curl = self::srJiraProcessHelper()->hook()->initJiraCurl();
    }
}
