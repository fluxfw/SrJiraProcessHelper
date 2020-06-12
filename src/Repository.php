<?php

namespace srag\Plugins\SrJiraProcessHelper;

use ilSrJiraProcessHelperPlugin;
use srag\DIC\SrJiraProcessHelper\DICTrait;
use srag\Plugins\SrJiraProcessHelper\Config\Repository as ConfigRepository;
use srag\Plugins\SrJiraProcessHelper\Hook\Repository as HookRepository;
use srag\Plugins\SrJiraProcessHelper\Job\Repository as JobsRepository;
use srag\Plugins\SrJiraProcessHelper\Utils\SrJiraProcessHelperTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrJiraProcessHelper
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
     *
     */
    public function dropTables()/* : void*/
    {
        $this->config()->dropTables();
        $this->hook()->dropTables();
        $this->jobs()->dropTables();
    }


    /**
     * @return ConfigRepository
     */
    public function config() : ConfigRepository
    {
        return ConfigRepository::getInstance();
    }


    /**
     * @return HookRepository
     */
    public function hook() : HookRepository
    {
        return HookRepository::getInstance();
    }


    /**
     * @return JobsRepository
     */
    public function jobs() : JobsRepository
    {
        return JobsRepository::getInstance();
    }


    /**
     *
     */
    public function installTables()/* : void*/
    {
        $this->config()->installTables();
        $this->hook()->installTables();
        $this->jobs()->installTables();
    }
}
