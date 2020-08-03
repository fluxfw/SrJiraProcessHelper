<?php

namespace srag\Plugins\SrJiraProcessHelper\Config\Form;

use ilSrJiraProcessHelperPlugin;
use ilTextInputGUI;
use srag\CustomInputGUIs\SrJiraProcessHelper\FormBuilder\AbstractFormBuilder;
use srag\CustomInputGUIs\SrJiraProcessHelper\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\CustomInputGUIs\SrJiraProcessHelper\MultiLineNewInputGUI\MultiLineNewInputGUI;
use srag\JiraCurl\SrJiraProcessHelper\JiraCurl;
use srag\Plugins\SrJiraProcessHelper\Config\ConfigCtrl;
use srag\Plugins\SrJiraProcessHelper\Utils\SrJiraProcessHelperTrait;

/**
 * Class FormBuilder
 *
 * @package srag\Plugins\SrJiraProcessHelper\Config\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FormBuilder extends AbstractFormBuilder
{

    use SrJiraProcessHelperTrait;

    const KEY_BEXIO_OFFER_EMAILS = "bexio_offer_emails";
    const KEY_BEXIO_OFFER_EMAILS_LINK_TYPE = "bexio_offer_emails_link_type";
    const KEY_BEXIO_OFFER_EMAILS_OFFER_URL_FIELD = "bexio_offer_emails_offer_url_field";
    const KEY_ENABLE_BEXIO_OFFER_EMAILS = "enable_bexio_offer_emails";
    const KEY_ENABLE_JIRA_WEB_HOOK = "enable_jira_web_hook";
    const KEY_ENABLE_MAPPING = "enable_mapping";
    //const KEY_JIRA_ACCESS_TOKEN = "jira_access_token";
    const KEY_JIRA_AUTHORIZATION = "jira_authorization";
    const KEY_JIRA_DOMAIN = "jira_domain";
    //const KEY_JIRA_CONSUMER_KEY = "jira_consumer_key";
    const KEY_JIRA_PASSWORD = "jira_password";
    //const KEY_JIRA_PRIVATE_KEY = "jira_private_key";
    const KEY_JIRA_USERNAME = "jira_username";
    const KEY_JIRA_WEB_HOOK_SECRET = "secret";
    const KEY_MAPPING = "mapping";
    const PLUGIN_CLASS_NAME = ilSrJiraProcessHelperPlugin::class;


    /**
     * @inheritDoc
     *
     * @param ConfigCtrl $parent
     */
    public function __construct(ConfigCtrl $parent)
    {
        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getButtons() : array
    {
        $buttons = [
            ConfigCtrl::CMD_UPDATE_CONFIGURE => self::plugin()->translate("save", ConfigCtrl::LANG_MODULE)
        ];

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = [
            "jira"                       => [
                self::KEY_JIRA_DOMAIN        => self::srJiraProcessHelper()->config()->getValue(self::KEY_JIRA_DOMAIN),
                self::KEY_JIRA_AUTHORIZATION => [
                    "value"        => self::srJiraProcessHelper()->config()->getValue(self::KEY_JIRA_AUTHORIZATION),
                    "group_values" => (function () : array {
                        switch (self::srJiraProcessHelper()->config()->getValue(self::KEY_JIRA_AUTHORIZATION)) {
                            case JiraCurl::AUTHORIZATION_USERNAMEPASSWORD:
                                return [
                                    self::KEY_JIRA_USERNAME => self::srJiraProcessHelper()->config()->getValue(self::KEY_JIRA_USERNAME),
                                    self::KEY_JIRA_PASSWORD => self::srJiraProcessHelper()->config()->getValue(self::KEY_JIRA_PASSWORD)
                                ];
                            /*case JiraCurl::AUTHORIZATION_OAUTH:
                                return [
                                    self::KEY_JIRA_CONSUMER_KEY => self::srJiraProcessHelper()->config()->getValue(self::KEY_JIRA_CONSUMER_KEY),
                                    self::KEY_JIRA_PRIVATE_KEY  => self::srJiraProcessHelper()->config()->getValue(self::KEY_JIRA_PRIVATE_KEY),
                                    self::KEY_JIRA_ACCESS_TOKEN => self::srJiraProcessHelper()->config()->getValue(self::KEY_JIRA_ACCESS_TOKEN)
                                ];*/
                            default:
                                return [];
                        }
                    })()
                ]
            ],
            "jira_web_hook"              => [
                self::KEY_ENABLE_JIRA_WEB_HOOK => self::srJiraProcessHelper()->config()->getValue(self::KEY_ENABLE_JIRA_WEB_HOOK),
                self::KEY_JIRA_WEB_HOOK_SECRET => self::srJiraProcessHelper()->config()->getValue(self::KEY_JIRA_WEB_HOOK_SECRET)
            ],
            self::KEY_MAPPING            => [
                self::KEY_ENABLE_MAPPING => self::srJiraProcessHelper()->config()->getValue(self::KEY_ENABLE_MAPPING),
                self::KEY_MAPPING        => self::srJiraProcessHelper()->config()->getValue(self::KEY_MAPPING)
            ],
            self::KEY_BEXIO_OFFER_EMAILS => [
                self::KEY_ENABLE_BEXIO_OFFER_EMAILS          => self::srJiraProcessHelper()->config()->getValue(self::KEY_ENABLE_BEXIO_OFFER_EMAILS),
                self::KEY_BEXIO_OFFER_EMAILS                 => self::srJiraProcessHelper()->config()->getValue(self::KEY_BEXIO_OFFER_EMAILS),
                self::KEY_BEXIO_OFFER_EMAILS_OFFER_URL_FIELD => self::srJiraProcessHelper()->config()->getValue(self::KEY_BEXIO_OFFER_EMAILS_OFFER_URL_FIELD),
                self::KEY_BEXIO_OFFER_EMAILS_LINK_TYPE       => self::srJiraProcessHelper()->config()->getValue(self::KEY_BEXIO_OFFER_EMAILS_LINK_TYPE)
            ]
        ];

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $jira_authorization_usernamepassword_fields = [
            self::KEY_JIRA_USERNAME => self::dic()->ui()->factory()->input()->field()->text(self::plugin()->translate(self::KEY_JIRA_USERNAME, ConfigCtrl::LANG_MODULE))->withRequired(true),
            self::KEY_JIRA_PASSWORD => self::dic()->ui()->factory()->input()->field()->password(self::plugin()->translate(self::KEY_JIRA_PASSWORD, ConfigCtrl::LANG_MODULE))->withRequired(true)
        ];
        /*$jira_authorization_oauth_fields = [
            self::KEY_JIRA_CONSUMER_KEY => self::dic()->ui()->factory()->input()->field()->text(self::plugin()->translate(self::KEY_JIRA_CONSUMER_KEY, ConfigCtrl::LANG_MODULE))->withRequired(true),
            self::KEY_JIRA_PRIVATE_KEY  => self::dic()->ui()->factory()->input()->field()->text(self::plugin()->translate(self::KEY_JIRA_PRIVATE_KEY, ConfigCtrl::LANG_MODULE))->withRequired(true),
            self::KEY_JIRA_ACCESS_TOKEN => self::dic()->ui()->factory()->input()->field()->text(self::plugin()->translate(self::KEY_JIRA_ACCESS_TOKEN, ConfigCtrl::LANG_MODULE))->withRequired(true)
        ];*/
        if (self::version()->is6()) {
            $jira_authorization = self::dic()->ui()->factory()->input()->field()->switchableGroup([
                JiraCurl::AUTHORIZATION_USERNAMEPASSWORD => self::dic()
                    ->ui()
                    ->factory()
                    ->input()
                    ->field()
                    ->group($jira_authorization_usernamepassword_fields,
                        self::plugin()->translate(self::KEY_JIRA_AUTHORIZATION . "_" . JiraCurl::AUTHORIZATION_USERNAMEPASSWORD, ConfigCtrl::LANG_MODULE))/*,
                JiraCurl::AUTHORIZATION_OAUTH            => self::dic()
                    ->ui()
                    ->factory()
                    ->input()
                    ->field()
                    ->group($jira_authorization_oauth_fields,
                        self::plugin()->translate(self::KEY_JIRA_AUTHORIZATION . "_" . JiraCurl::AUTHORIZATION_OAUTH, ConfigCtrl::LANG_MODULE))*/
            ], self::plugin()->translate(self::KEY_JIRA_AUTHORIZATION, ConfigCtrl::LANG_MODULE))->withRequired(true);
        } else {
            $jira_authorization = self::dic()
                ->ui()
                ->factory()
                ->input()
                ->field()
                ->radio(self::plugin()->translate(self::KEY_JIRA_AUTHORIZATION, ConfigCtrl::LANG_MODULE))
                ->withRequired(true)
                ->withOption(JiraCurl::AUTHORIZATION_USERNAMEPASSWORD,
                    self::plugin()->translate(self::KEY_JIRA_AUTHORIZATION . "_" . JiraCurl::AUTHORIZATION_USERNAMEPASSWORD, ConfigCtrl::LANG_MODULE), null,
                    $jira_authorization_usernamepassword_fields)/*->withOption(JiraCurl::AUTHORIZATION_OAUTH,
                    self::plugin()->translate(self::KEY_JIRA_AUTHORIZATION . "_" . JiraCurl::AUTHORIZATION_OAUTH, ConfigCtrl::LANG_MODULE), null,
                    $jira_authorization_oauth_fields)*/
            ;
        }

        $mapping = (new InputGUIWrapperUIInputComponent(new MultiLineNewInputGUI(self::plugin()
            ->translate(self::KEY_MAPPING, ConfigCtrl::LANG_MODULE))));
        $mapping->getInput()->setShowSort(false);
        $input = new ilTextInputGUI(self::plugin()
            ->translate(self::KEY_MAPPING . "_email_domain", ConfigCtrl::LANG_MODULE), "email_domain");
        $input->setRequired(true);
        $mapping->getInput()->addInput($input);
        $input = new ilTextInputGUI(self::plugin()
            ->translate(self::KEY_MAPPING . "_assign_jira_user", ConfigCtrl::LANG_MODULE), "assign_jira_user");
        $input->setRequired(true);
        $mapping->getInput()->addInput($input);

        $bexio_mails = (new InputGUIWrapperUIInputComponent(new MultiLineNewInputGUI(self::plugin()
            ->translate(self::KEY_BEXIO_OFFER_EMAILS, ConfigCtrl::LANG_MODULE))));
        $bexio_mails->getInput()->setShowSort(false);
        $input = new ilTextInputGUI(self::plugin()
            ->translate(self::KEY_BEXIO_OFFER_EMAILS . "_email_address", ConfigCtrl::LANG_MODULE), "email_address");
        $input->setRequired(true);
        $bexio_mails->getInput()->addInput($input);

        $fields = [
            "jira"                       => self::dic()->ui()->factory()->input()->field()->section([
                self::KEY_JIRA_DOMAIN        => self::dic()->ui()->factory()->input()->field()->text(self::plugin()->translate(self::KEY_JIRA_DOMAIN, ConfigCtrl::LANG_MODULE))->withRequired(true),
                self::KEY_JIRA_AUTHORIZATION => $jira_authorization
            ], self::plugin()->translate("jira", ConfigCtrl::LANG_MODULE)),
            "jira_web_hook"              => self::dic()->ui()->factory()->input()->field()->section([
                self::KEY_ENABLE_JIRA_WEB_HOOK => self::dic()->ui()->factory()->input()->field()->checkbox(self::plugin()->translate("enable", ConfigCtrl::LANG_MODULE)),
                self::KEY_JIRA_WEB_HOOK_SECRET => self::dic()->ui()->factory()->input()->field()->password(self::plugin()
                    ->translate(self::KEY_JIRA_WEB_HOOK_SECRET, ConfigCtrl::LANG_MODULE))->withRequired(true)
            ], self::plugin()->translate("jira_web_hook", ConfigCtrl::LANG_MODULE)),
            self::KEY_MAPPING            => self::dic()->ui()->factory()->input()->field()->section([
                self::KEY_ENABLE_MAPPING => self::dic()->ui()->factory()->input()->field()->checkbox(self::plugin()->translate("enable", ConfigCtrl::LANG_MODULE)),
                self::KEY_MAPPING        => $mapping
            ], self::plugin()->translate(self::KEY_MAPPING, ConfigCtrl::LANG_MODULE)),
            self::KEY_BEXIO_OFFER_EMAILS => self::dic()->ui()->factory()->input()->field()->section([
                self::KEY_ENABLE_BEXIO_OFFER_EMAILS          => self::dic()->ui()->factory()->input()->field()->checkbox(self::plugin()
                    ->translate("enable", ConfigCtrl::LANG_MODULE)),
                self::KEY_BEXIO_OFFER_EMAILS                 => $bexio_mails,
                self::KEY_BEXIO_OFFER_EMAILS_OFFER_URL_FIELD => self::dic()->ui()->factory()->input()->field()->text(self::plugin()
                    ->translate(self::KEY_BEXIO_OFFER_EMAILS_OFFER_URL_FIELD, ConfigCtrl::LANG_MODULE))->withRequired(true),
                self::KEY_BEXIO_OFFER_EMAILS_LINK_TYPE       => self::dic()->ui()->factory()->input()->field()->text(self::plugin()
                    ->translate(self::KEY_BEXIO_OFFER_EMAILS_LINK_TYPE, ConfigCtrl::LANG_MODULE))->withRequired(true),
            ], self::plugin()->translate(self::KEY_BEXIO_OFFER_EMAILS, ConfigCtrl::LANG_MODULE))
        ];

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function getTitle() : string
    {
        return self::plugin()->translate("configuration", ConfigCtrl::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data)/* : void*/
    {
        self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_DOMAIN, strval($data["jira"][self::KEY_JIRA_DOMAIN]));
        if (self::version()->is6()) {
            switch (strval($data["jira"][self::KEY_JIRA_AUTHORIZATION][0])) {
                case JiraCurl::AUTHORIZATION_USERNAMEPASSWORD;
                    self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_AUTHORIZATION, JiraCurl::AUTHORIZATION_USERNAMEPASSWORD);
                    self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_USERNAME, strval($data["jira"][self::KEY_JIRA_AUTHORIZATION][1][self::KEY_JIRA_USERNAME]));
                    self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_PASSWORD, $data["jira"][self::KEY_JIRA_AUTHORIZATION][1][self::KEY_JIRA_PASSWORD]->toString());
                    break;
                /*case JiraCurl::AUTHORIZATION_OAUTH;
                    self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_AUTHORIZATION, JiraCurl::AUTHORIZATION_OAUTH);
                    self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_CONSUMER_KEY, strval($data["jira"][self::KEY_JIRA_AUTHORIZATION][1][self::KEY_JIRA_CONSUMER_KEY]));
                    self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_PRIVATE_KEY, strval($data["jira"][self::KEY_JIRA_AUTHORIZATION][1][self::KEY_JIRA_PRIVATE_KEY]));
                    self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_ACCESS_TOKEN, strval($data["jira"][self::KEY_JIRA_AUTHORIZATION][1][self::KEY_JIRA_ACCESS_TOKEN]));
                    break;*/
                default:
                    break;
            }
        } else {
            switch (strval($data["jira"][self::KEY_JIRA_AUTHORIZATION]["value"])) {
                case JiraCurl::AUTHORIZATION_USERNAMEPASSWORD;
                    self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_AUTHORIZATION, JiraCurl::AUTHORIZATION_USERNAMEPASSWORD);
                    self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_USERNAME, strval($data["jira"][self::KEY_JIRA_AUTHORIZATION]["group_values"][self::KEY_JIRA_USERNAME]));
                    self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_PASSWORD, $data["jira"][self::KEY_JIRA_AUTHORIZATION]["group_values"][self::KEY_JIRA_PASSWORD]->toString());
                    break;
                /*case JiraCurl::AUTHORIZATION_OAUTH;
                    self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_AUTHORIZATION, JiraCurl::AUTHORIZATION_OAUTH);
                    self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_CONSUMER_KEY, strval($data["jira"][self::KEY_JIRA_AUTHORIZATION]["group_values"][self::KEY_JIRA_CONSUMER_KEY]));
                    self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_PRIVATE_KEY, strval($data["jira"][self::KEY_JIRA_AUTHORIZATION]["group_values"][self::KEY_JIRA_PRIVATE_KEY]));
                    self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_ACCESS_TOKEN, strval($data["jira"][self::KEY_JIRA_AUTHORIZATION]["group_values"][self::KEY_JIRA_ACCESS_TOKEN]));
                    break;*/
                default:
                    break;
            }
        }
        self::srJiraProcessHelper()->config()->setValue(self::KEY_ENABLE_JIRA_WEB_HOOK, boolval($data["jira_web_hook"][self::KEY_ENABLE_JIRA_WEB_HOOK]));
        self::srJiraProcessHelper()->config()->setValue(self::KEY_JIRA_WEB_HOOK_SECRET, $data["jira_web_hook"][self::KEY_JIRA_WEB_HOOK_SECRET]->toString());
        self::srJiraProcessHelper()->config()->setValue(self::KEY_ENABLE_MAPPING, boolval($data[self::KEY_MAPPING][self::KEY_ENABLE_MAPPING]));
        self::srJiraProcessHelper()->config()->setValue(self::KEY_MAPPING, (array) $data[self::KEY_MAPPING][self::KEY_MAPPING]);
        self::srJiraProcessHelper()->config()->setValue(self::KEY_ENABLE_BEXIO_OFFER_EMAILS, boolval($data[self::KEY_BEXIO_OFFER_EMAILS][self::KEY_ENABLE_BEXIO_OFFER_EMAILS]));
        self::srJiraProcessHelper()->config()->setValue(self::KEY_BEXIO_OFFER_EMAILS, (array) $data[self::KEY_BEXIO_OFFER_EMAILS][self::KEY_BEXIO_OFFER_EMAILS]);
        self::srJiraProcessHelper()
            ->config()
            ->setValue(self::KEY_BEXIO_OFFER_EMAILS_OFFER_URL_FIELD, strval($data[self::KEY_BEXIO_OFFER_EMAILS][self::KEY_BEXIO_OFFER_EMAILS_OFFER_URL_FIELD]));
        self::srJiraProcessHelper()->config()->setValue(self::KEY_BEXIO_OFFER_EMAILS_LINK_TYPE, strval($data[self::KEY_BEXIO_OFFER_EMAILS][self::KEY_BEXIO_OFFER_EMAILS_LINK_TYPE]));
    }
}
