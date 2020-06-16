<?php

namespace srag\Plugins\SrJiraProcessHelper\Hook;

use Exception;
use ilLogLevel;
use ilSrJiraProcessHelperPlugin;
use srag\DIC\SrJiraProcessHelper\DICTrait;
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
     * Hook constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function handle()/*:void*/
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

            $jira_curl = self::srJiraProcessHelper()->hook()->initJiraCurl();

            $issue = $jira_curl->getTicketByKey($post["issue_key"]);
            if (
                empty($issue)
                || empty($issue["key"])
                || empty($issue["fields"]["creator"]["emailAddress"])
            ) {
                throw new Exception("Invalid issue result");
            }

            $email = $issue["fields"]["creator"]["emailAddress"];
            $email_domain = explode("@", $email)[1];
            if (empty($email_domain)) {
                throw new Exception("Invalid email address of creator " . $email);
            }

            $assigned = false;
            foreach (self::srJiraProcessHelper()->config()->getValue(FormBuilder::KEY_MAPPING) as $mapping) {
                if ($mapping["email_domain"] === $email_domain) {
                    $jira_curl->assignIssueToUser($issue["key"], $mapping["assign_jira_user"]);
                    $assigned = true;
                    break;
                }
            }
            if (!$assigned) {
                throw new Exception("No Jira user found for email domain " . $email_domain);
            }
        } catch (Throwable $ex) {
            self::dic()->logger()->root()->log($ex->__toString(), ilLogLevel::ERROR);
        }
    }
}
