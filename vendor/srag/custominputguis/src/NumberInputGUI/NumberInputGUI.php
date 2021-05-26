<?php

namespace srag\CustomInputGUIs\SrJiraProcessHelper\NumberInputGUI;

use ilNumberInputGUI;
use ilTableFilterItem;
use ilToolbarItem;
use srag\DIC\SrJiraProcessHelper\DICTrait;

/**
 * Class NumberInputGUI
 *
 * @package srag\CustomInputGUIs\SrJiraProcessHelper\NumberInputGUI
 */
class NumberInputGUI extends ilNumberInputGUI implements ilTableFilterItem, ilToolbarItem
{

    use DICTrait;

    /**
     * @inheritDoc
     */
    public function getTableFilterHTML() : string
    {
        return $this->render();
    }


    /**
     * @inheritDoc
     */
    public function getToolbarHTML() : string
    {
        return $this->render();
    }
}
