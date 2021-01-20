<?php

namespace srag\Plugins\SrJiraProcessHelper\WebHook;

use Exception;
use ilCurlConnection;
use ilProxySettings;
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
     * @param string $reporter_email_domain
     *
     * @return array
     */
    public function getSrdbContacts(string $reporter_email_domain) : array
    {
        $rest_url = "contacts";

        $headers = [
            "Accept" => "application/json"
        ];

        $result = $this->doSrdbRequest($rest_url, $headers);

        $contacts = json_decode($result, true);

        if (!is_array($contacts)) {
            throw new Exception("Invalid srdb contacts");
        }

        return array_filter($contacts, function (array $contact) use ($reporter_email_domain) : bool {
            return (strrpos($contact["email"], "@" . $reporter_email_domain) === (strlen($contact["email"]) - strlen("@" . $reporter_email_domain)));
        });
    }


    /**
     * @param int[] $product_ids
     *
     * @return array
     */
    public function getSrdbProducts(array $product_ids) : array
    {
        $products = [];

        foreach ($product_ids as $product_id) {
            $rest_url = "products/" . $product_id;

            $headers = [
                "Accept" => "application/json"
            ];

            $result = $this->doSrdbRequest($rest_url, $headers);

            $product = json_decode($result, true);

            if (!is_array($product)) {
                throw new Exception("Invalid srdb products");
            }

            $products [] = $product;
        }

        return $products;
    }


    /**
     * @param int[]  $sla_ids
     * @param string $type
     *
     * @return array
     */
    public function getSrdbSlas(array $sla_ids, string $type) : array
    {
        $slas = [];

        foreach ($sla_ids as $sla_id) {
            $rest_url = "slas/" . $sla_id;

            $headers = [
                "Accept" => "application/json"
            ];

            $result = $this->doSrdbRequest($rest_url, $headers);

            $sla = json_decode($result, true);

            if (!is_array($sla)) {
                throw new Exception("Invalid srdb slas");
            }

            $slas[] = $sla;
        }

        return array_filter($slas, function (array $sla) use ($type) : bool {
            return ($sla["type"] === $type);
        });
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


    /**
     * @param string $rest_url
     * @param array  $headers
     *
     * @return string|null
     */
    private function doSrdbRequest(string $rest_url, array $headers) : ?string
    {
        $curlConnection = null;

        try {
            $curlConnection = $this->initSrdbCurl($rest_url, $headers);

            $result = $curlConnection->exec();

            return $result;
        } finally {
            if ($curlConnection !== null) {
                $curlConnection->close();
                $curlConnection = null;
            }
        }
    }


    /**
     * @param string $rest_url
     * @param array  $headers
     *
     * @return ilCurlConnection
     */
    private function initSrdbCurl(string $rest_url, array $headers) : ilCurlConnection
    {
        $curlConnection = new ilCurlConnection(self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_SRDB_DOMAIN) . "/api/" . $rest_url);

        $curlConnection->init();

        if (!self::version()->is6()) {
            $proxy = ilProxySettings::_getInstance();
            if ($proxy->isActive()) {
                $curlConnection->setOpt(CURLOPT_HTTPPROXYTUNNEL, true);

                if (!empty($proxy->getHost())) {
                    $curlConnection->setOpt(CURLOPT_PROXY, $proxy->getHost());
                }

                if (!empty($proxy->getPort())) {
                    $curlConnection->setOpt(CURLOPT_PROXYPORT, $proxy->getPort());
                }
            }
        }

        $curlConnection->setOpt(CURLOPT_USERPWD, self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_SRDB_USERNAME) . ":" . self::srJiraProcessHelper()
                ->config()
                ->getValue(FormBuilder::KEY_SRDB_PASSWORD));

        $headers["User-Agent"] = "ILIAS " . self::version()->getILIASVersion();
        $curlConnection->setOpt(CURLOPT_HTTPHEADER, array_map(function (string $key, string $value) : string {
            return ($key . ": " . $value);
        }, array_keys($headers), $headers));

        $curlConnection->setOpt(CURLOPT_FOLLOWLOCATION, true);

        $curlConnection->setOpt(CURLOPT_RETURNTRANSFER, true);

        $curlConnection->setOpt(CURLOPT_VERBOSE, false/*(intval(DEVMODE) === 1)*/);

        return $curlConnection;
    }
}
