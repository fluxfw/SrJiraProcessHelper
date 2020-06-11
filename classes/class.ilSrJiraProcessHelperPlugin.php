<?php

require_once __DIR__ . "/../vendor/autoload.php";

use ILIAS\DI\Container;
use srag\CustomInputGUIs\SrJiraProcessHelper\Loader\CustomInputGUIsLoaderDetector;
use srag\Plugins\SrJiraProcessHelper\Utils\SrJiraProcessHelperTrait;
use srag\RemovePluginDataConfirm\SrJiraProcessHelper\PluginUninstallTrait;

/**
 * Class ilSrJiraProcessHelperPlugin
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrJiraProcessHelperPlugin extends ilUserInterfaceHookPlugin
{

    use PluginUninstallTrait;
    use SrJiraProcessHelperTrait;

    const PLUGIN_ID = "srjiprohe";
    const PLUGIN_NAME = "SrJiraProcessHelper";
    const PLUGIN_CLASS_NAME = self::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * ilSrJiraProcessHelperPlugin constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @inheritDoc
     */
    public function getPluginName() : string
    {
        return self::PLUGIN_NAME;
    }


    /**
     * @inheritDoc
     */
    protected function shouldUseOneUpdateStepOnly() : bool
    {
        return true;
    }


    /**
     * @inheritDoc
     */
    public function updateLanguages(/*?array*/ $a_lang_keys = null)/* : void*/
    {
        parent::updateLanguages($a_lang_keys);

        $this->installRemovePluginDataConfirmLanguages();
    }


    /**
     * @inheritDoc
     */
    protected function deleteData()/* : void*/
    {
        self::srJiraProcessHelper()->dropTables();
    }


    /**
     * @inheritDoc
     */
    public function exchangeUIRendererAfterInitialization(Container $dic) : Closure
    {
        return CustomInputGUIsLoaderDetector::exchangeUIRendererAfterInitialization();
    }
}
