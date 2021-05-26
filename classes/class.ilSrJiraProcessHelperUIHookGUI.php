<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\SrJiraProcessHelper\DICTrait;
use srag\Plugins\SrJiraProcessHelper\Utils\SrJiraProcessHelperTrait;

/**
 * Class ilSrJiraProcessHelperUIHookGUI
 */
class ilSrJiraProcessHelperUIHookGUI extends ilUIHookPluginGUI
{

    use DICTrait;
    use SrJiraProcessHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrJiraProcessHelperPlugin::class;


    /**
     * @inheritDoc
     */
    public function gotoHook() : void
    {
        $target = filter_input(INPUT_GET, "target");

        $matches = [];
        preg_match("/^uihk_" . ilSrJiraProcessHelperPlugin::PLUGIN_ID . "$/uim", $target, $matches);

        if (is_array($matches) && count($matches) >= 1) {
            self::srJiraProcessHelper()->webHook()->factory()->newInstance()->handle();
            exit;
        }
    }
}
