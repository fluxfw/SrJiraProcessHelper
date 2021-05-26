<?php

namespace srag\Plugins\SrJiraProcessHelper\Config;

use ilSrJiraProcessHelperPlugin;
use srag\ActiveRecordConfig\SrJiraProcessHelper\Config\AbstractFactory;
use srag\Plugins\SrJiraProcessHelper\Config\Form\FormBuilder;
use srag\Plugins\SrJiraProcessHelper\Utils\SrJiraProcessHelperTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrJiraProcessHelper\Config
 */
final class Factory extends AbstractFactory
{

    use SrJiraProcessHelperTrait;

    const PLUGIN_CLASS_NAME = ilSrJiraProcessHelperPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
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
     * @param ConfigCtrl $parent
     *
     * @return FormBuilder
     */
    public function newFormBuilderInstance(ConfigCtrl $parent) : FormBuilder
    {
        $form = new FormBuilder($parent);

        return $form;
    }
}
