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


    /**
     * @inheritDoc
     */
    public function gotoHook()/*: void*/
    {
        $target = filter_input(INPUT_GET, "target");

        $matches = [];
        preg_match("/^uihk_" . ilSrJiraProcessHelperPlugin::PLUGIN_ID . "$/uim", $target, $matches);

        if (is_array($matches) && count($matches) >= 1) {
            self::srJiraProcessHelper()->hook()->factory()->newInstance()->handle();
            exit;
        }
    }
}
