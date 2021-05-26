<?php

namespace srag\CustomInputGUIs\SrJiraProcessHelper\HiddenInputGUI;

use ilHiddenInputGUI;
use srag\CustomInputGUIs\SrJiraProcessHelper\Template\Template;
use srag\DIC\SrJiraProcessHelper\DICTrait;

/**
 * Class HiddenInputGUI
 *
 * @package srag\CustomInputGUIs\SrJiraProcessHelper\HiddenInputGUI
 */
class HiddenInputGUI extends ilHiddenInputGUI
{

    use DICTrait;

    /**
     * HiddenInputGUI constructor
     *
     * @param string $a_postvar
     */
    public function __construct(string $a_postvar = "")
    {
        parent::__construct($a_postvar);
    }


    /**
     * @return string
     */
    public function render() : string
    {
        $tpl = new Template("Services/Form/templates/default/tpl.property_form.html", true, true);

        $this->insert($tpl);

        return self::output()->getHTML($tpl);
    }
}
