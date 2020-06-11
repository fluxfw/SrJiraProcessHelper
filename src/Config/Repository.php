<?php

namespace srag\Plugins\SrJiraProcessHelper\Config;

use ilSrJiraProcessHelperPlugin;
use srag\ActiveRecordConfig\SrJiraProcessHelper\Config\AbstractFactory;
use srag\ActiveRecordConfig\SrJiraProcessHelper\Config\AbstractRepository;
use srag\ActiveRecordConfig\SrJiraProcessHelper\Config\Config;
use srag\JiraCurl\SrJiraProcessHelper\JiraCurl;
use srag\Plugins\SrJiraProcessHelper\Config\Form\FormBuilder;
use srag\Plugins\SrJiraProcessHelper\Utils\SrJiraProcessHelperTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrJiraProcessHelper\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository extends AbstractRepository
{

    use SrJiraProcessHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrJiraProcessHelperPlugin::class;
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
     * Repository constructor
     */
    protected function __construct()
    {
        parent::__construct();
    }


    /**
     * @inheritDoc
     *
     * @return Factory
     */
    public function factory() : AbstractFactory
    {
        return Factory::getInstance();
    }


    /**
     * @inheritDoc
     */
    protected function getTableName() : string
    {
        return ilSrJiraProcessHelperPlugin::PLUGIN_ID . "_config";
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        return [
            FormBuilder::KEY_JIRA_AUTHORIZATION => [Config::TYPE_STRING, JiraCurl::AUTHORIZATION_USERNAMEPASSWORD],
            FormBuilder::KEY_JIRA_DOMAIN        => Config::TYPE_STRING,
            FormBuilder::KEY_JIRA_PASSWORD      => Config::TYPE_STRING,
            FormBuilder::KEY_JIRA_USERNAME      => Config::TYPE_STRING
        ];
    }
}
