<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DevTools\SrJiraProcessHelper\DevToolsCtrl;
use srag\DIC\SrJiraProcessHelper\DICTrait;
use srag\Plugins\SrJiraProcessHelper\Config\ConfigCtrl;
use srag\Plugins\SrJiraProcessHelper\Utils\SrJiraProcessHelperTrait;

/**
 * Class ilSrJiraProcessHelperConfigGUI
 *
 * @ilCtrl_isCalledBy srag\DevTools\SrJiraProcessHelper\DevToolsCtrl: ilSrJiraProcessHelperConfigGUI
 */
class ilSrJiraProcessHelperConfigGUI extends ilPluginConfigGUI
{

    use DICTrait;
    use SrJiraProcessHelperTrait;

    const CMD_CONFIGURE = "configure";
    const PLUGIN_CLASS_NAME = ilSrJiraProcessHelperPlugin::class;


    /**
     * ilSrJiraProcessHelperConfigGUI constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function performCommand(/*string*/ $cmd) : void
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(ConfigCtrl::class):
                self::dic()->ctrl()->forwardCommand(new ConfigCtrl());
                break;

            case strtolower(DevToolsCtrl::class):
                self::dic()->ctrl()->forwardCommand(new DevToolsCtrl($this, self::plugin()));
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_CONFIGURE:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function configure() : void
    {
        self::dic()->ctrl()->redirectByClass(ConfigCtrl::class, ConfigCtrl::CMD_CONFIGURE);
    }


    /**
     *
     */
    protected function setTabs() : void
    {
        ConfigCtrl::addTabs();

        DevToolsCtrl::addTabs(self::plugin());

        self::dic()->locator()->addItem(ilSrJiraProcessHelperPlugin::PLUGIN_NAME, self::dic()->ctrl()->getLinkTarget($this, self::CMD_CONFIGURE));
    }
}
