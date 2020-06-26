<?php

namespace srag\Plugins\SrJiraProcessHelper\Hook;

use Exception;
use ilLogLevel;
use ilSrJiraProcessHelperPlugin;
use srag\DIC\SrJiraProcessHelper\DICTrait;
use srag\JiraCurl\SrJiraProcessHelper\JiraCurl;
use srag\Plugins\SrJiraProcessHelper\Config\Form\FormBuilder;
use srag\Plugins\SrJiraProcessHelper\Utils\SrJiraProcessHelperTrait;
use Throwable;

/**
 * Class Hook
 *
 * @package srag\Plugins\SrJiraProcessHelper\Hook
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Hook
{

    use DICTrait;
    use SrJiraProcessHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrJiraProcessHelperPlugin::class;
    /**
     * @var array
     */
    protected $issue;
    /**
     * @var JiraCurl
     */
    protected $jira_curl;


    /**
     * Hook constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function handle()/* : void*/
    {
        try {
            $post = json_decode(file_get_contents("php://input"), true);
            if (
                !is_array($post)
                || empty($post["issue_key"])
                || empty($post["secret"])
                || $post["secret"] !== self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_SECRET)
            ) {
                throw new Exception("Invalid post input");
            }

            $this->jira_curl = self::srJiraProcessHelper()->hook()->initJiraCurl();

            $this->issue = $this->jira_curl->getTicketByKey($post["issue_key"]);
            if (
                empty($this->issue)
                || empty($this->issue["key"])
                || empty($this->issue["fields"]["creator"]["emailAddress"])
            ) {
                throw new Exception("Invalid issue result");
            }

            try {
                $this->handleMapping();
            } catch (Throwable $ex) {
                self::dic()->logger()->root()->log($ex->__toString(), ilLogLevel::ERROR);
            }

            try {
                $this->handleBexioOfferEmails();
            } catch (Throwable $ex) {
                self::dic()->logger()->root()->log($ex->__toString(), ilLogLevel::ERROR);
            }
        } catch (Throwable $ex) {
            self::dic()->logger()->root()->log($ex->__toString(), ilLogLevel::ERROR);
        }
    }


    /**
     *
     */
    protected function handleBexioOfferEmails()/* : void*/
    {
        $reporter_email = $this->issue["fields"]["reporter"]["emailAddress"];
        if (empty(array_filter(self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_BEXIO_OFFER_EMAILS), function (array $bexio_offer_email) use ($reporter_email): bool {
            return ($bexio_offer_email["email_address"] === $reporter_email);
        }))
        ) {
            return;
        };

        $issue_text = $this->issue["fields"]["description"];
        $issue_text_lines = explode("\n", $issue_text);
        $issue_text_last_line = trim(end($issue_text_lines));
        if (empty($issue_text_last_line)) {
            throw new Exception("Invalid bexio offer url at last text line");
        }

        foreach (
            $this->jira_curl->getTicketsByJQL($this->jira_curl->escapeJQLValue(self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_BEXIO_OFFER_EMAILS_OFFER_URL_FIELD)) . "="
                . $this->jira_curl->escapeJQLValue($issue_text_last_line)) as $issue
        ) {
            if (
                empty($issue)
                || empty($issue["key"])
            ) {
                throw new Exception("Invalid issue result");
            }

            $this->jira_curl->linkTickets($this->issue["key"], $issue["key"], self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_BEXIO_OFFER_EMAILS_LINK_TYPE));
        }
    }


    /**
     *
     */
    protected function handleMapping()/* : void*/
    {
        $reporter_email = $this->issue["fields"]["reporter"]["emailAddress"];
        $reporter_email_domain = explode("@", $reporter_email)[1];
        if (empty($reporter_email_domain)) {
            throw new Exception("Invalid email address of reporter");
        }

        $assigned = false;
        foreach (self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_MAPPING) as $mapping) {
            if ($mapping["email_domain"] === $reporter_email_domain) {
                $this->jira_curl->assignIssueToUser($this->issue["key"], $mapping["assign_jira_user"]);
                $assigned = true;
                break;
            }
        }
        if (!$assigned) {
            throw new Exception("No Jira user found for email domain " . $reporter_email_domain);
        }
    }
}
