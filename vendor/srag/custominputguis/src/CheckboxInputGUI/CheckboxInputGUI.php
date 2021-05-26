<?php

namespace srag\CustomInputGUIs\SrJiraProcessHelper\CheckboxInputGUI;

use ilCheckboxInputGUI;
use ilTableFilterItem;
use srag\DIC\SrJiraProcessHelper\DICTrait;

/**
 * Class CheckboxInputGUI
 *
 * @package srag\CustomInputGUIs\SrJiraProcessHelper\CheckboxInputGUI
 */
class CheckboxInputGUI extends ilCheckboxInputGUI implements ilTableFilterItem
{

    use DICTrait;
}
