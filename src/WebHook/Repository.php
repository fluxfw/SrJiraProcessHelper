<?php

namespace srag\Plugins\SrJiraProcessHelper\WebHook;

use ilSrJiraProcessHelperPlugin;
use srag\DIC\SrJiraProcessHelper\DICTrait;
use srag\JiraCurl\SrJiraProcessHelper\JiraCurl;
use srag\Plugins\SrJiraProcessHelper\Config\Form\FormBuilder;
use srag\Plugins\SrJiraProcessHelper\Utils\SrJiraProcessHelperTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrJiraProcessHelper\WebHook
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
     * @internal
     */
    public function dropTables() : void
    {

    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @return JiraCurl
     */
    public function initJiraCurl() : JiraCurl
    {
        $jira_curl = new JiraCurl();

        $jira_curl->setJiraDomain(self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_JIRA_DOMAIN));

        $jira_curl->setJiraAuthorization(self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_JIRA_AUTHORIZATION));

        $jira_curl->setJiraUsername(self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_JIRA_USERNAME));
        $jira_curl->setJiraPassword(self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_JIRA_PASSWORD));

        /*$jira_curl->setJiraConsumerKey(self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_JIRA_CONSUMER_KEY));
        $jira_curl->setJiraPrivateKey(self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_JIRA_PRIVATE_KEY));
        $jira_curl->setJiraAccessToken(self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_JIRA_ACCESS_TOKEN));*/

        return $jira_curl;
    }


    /**
     * @internal
     */
    public function installTables() : void
    {

    }
}
