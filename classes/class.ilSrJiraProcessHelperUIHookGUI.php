<?php

use srag\DIC\SrJiraProcessHelper\DICTrait;
use srag\Plugins\SrJiraProcessHelper\Utils\SrJiraProcessHelperTrait;

/**
 * Class ilSrJiraProcessHelperUIHookGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrJiraProcessHelperUIHookGUI extends ilUIHookPluginGUI
{

    use DICTrait;
    use SrJiraProcessHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrJiraProcessHelperPlugin::class;
}
