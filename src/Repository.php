<?php

namespace srag\Plugins\SrJiraProcessHelper;

use ilSrJiraProcessHelperPlugin;
use srag\DIC\SrJiraProcessHelper\DICTrait;
use srag\Plugins\SrJiraProcessHelper\Config\Repository as ConfigRepository;
use srag\Plugins\SrJiraProcessHelper\Utils\SrJiraProcessHelperTrait;
use srag\Plugins\SrJiraProcessHelper\WebHook\Repository as WebHookRepository;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrJiraProcessHelper
 */
final class Repository
{

    use DICTrait;
    use SrJiraProcessHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrJiraProcessHelperPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Repository constructor
     */
    private function __construct()
    {

    }


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
     * @return ConfigRepository
     */
    public function config() : ConfigRepository
    {
        return ConfigRepository::getInstance();
    }


    /**
     *
     */
    public function dropTables() : void
    {
        $this->config()->dropTables();
        $this->webHook()->dropTables();
    }


    /**
     *
     */
    public function installTables() : void
    {
        $this->config()->installTables();
        $this->webHook()->installTables();
    }


    /**
     * @return WebHookRepository
     */
    public function webHook() : WebHookRepository
    {
        return WebHookRepository::getInstance();
    }
}
