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
     * Repository constructor
     */
    protected function __construct()
    {
        parent::__construct();
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
    public function setValue(string $name, $value) : void
    {
        if ($name === FormBuilder::KEY_MAPPING) {
            $value = array_map(function (array $mapping) : array {
                $mapping["email_domain"] = ltrim(trim($mapping["email_domain"]), "@");
                $mapping["assign_jira_user"] = trim($mapping["assign_jira_user"]);

                return $mapping;
            }, $value);

            usort($value, function (array $mapping1, array $mapping2) : int {
                $n1 = $mapping1["email_domain"];
                $n2 = $mapping2["email_domain"];

                return strnatcasecmp($n1, $n2);
            });
        }

        if ($name === FormBuilder::KEY_BEXIO_OFFER_EMAILS) {
            $value = array_map(function (array $mapping) : array {
                $mapping["email_address"] = trim($mapping["email_address"]);

                return $mapping;
            }, $value);

            usort($value, function (array $mapping1, array $mapping2) : int {
                $n1 = $mapping1["email_address"];
                $n2 = $mapping2["email_address"];

                return strnatcasecmp($n1, $n2);
            });
        }

        parent::setValue($name, $value);
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        return [
            FormBuilder::KEY_BEXIO_OFFER_EMAILS                 => [Config::TYPE_JSON, [], true],
            FormBuilder::KEY_BEXIO_OFFER_EMAILS_LINK_TYPE       => Config::TYPE_STRING,
            FormBuilder::KEY_BEXIO_OFFER_EMAILS_OFFER_URL_FIELD => Config::TYPE_STRING,
            FormBuilder::KEY_ENABLE_BEXIO_OFFER_EMAILS          => Config::TYPE_BOOLEAN,
            FormBuilder::KEY_ENABLE_JIRA_WEB_HOOK               => Config::TYPE_BOOLEAN,
            FormBuilder::KEY_ENABLE_MAPPING                     => Config::TYPE_BOOLEAN,
            FormBuilder::KEY_ENABLE_MARK_SLA                    => Config::TYPE_BOOLEAN,
            //FormBuilder::KEY_JIRA_ACCESS_TOKEN  => Config::TYPE_STRING,
            FormBuilder::KEY_JIRA_AUTHORIZATION                 => [Config::TYPE_STRING, JiraCurl::AUTHORIZATION_USERNAMEPASSWORD],
            //FormBuilder::KEY_JIRA_CONSUMER_KEY  => Config::TYPE_STRING,
            FormBuilder::KEY_JIRA_DOMAIN                        => Config::TYPE_STRING,
            FormBuilder::KEY_JIRA_PASSWORD                      => Config::TYPE_STRING,
            //FormBuilder::KEY_JIRA_PRIVATE_KEY   => Config::TYPE_STRING,
            FormBuilder::KEY_JIRA_USERNAME                      => Config::TYPE_STRING,
            FormBuilder::KEY_JIRA_WEB_HOOK_SECRET               => Config::TYPE_STRING,
            FormBuilder::KEY_MAPPING                            => [Config::TYPE_JSON, [], true],
            FormBuilder::KEY_MARK_SLA_SLAS_FIELD                => Config::TYPE_STRING,
            FormBuilder::KEY_MARK_SLA_TYPE                      => Config::TYPE_STRING,
            FormBuilder::KEY_SRDB_DOMAIN                        => Config::TYPE_STRING,
            FormBuilder::KEY_SRDB_PASSWORD                      => Config::TYPE_STRING,
            FormBuilder::KEY_SRDB_USERNAME                      => Config::TYPE_STRING
        ];
    }


    /**
     * @inheritDoc
     */
    protected function getTableName() : string
    {
        return ilSrJiraProcessHelperPlugin::PLUGIN_ID . "_config";
    }
}
