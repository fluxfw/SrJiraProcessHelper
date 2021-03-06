<?php

namespace srag\DIC\SrJiraProcessHelper\Plugin;

/**
 * Interface Pluginable
 *
 * @package srag\DIC\SrJiraProcessHelper\Plugin
 */
interface Pluginable
{

    /**
     * @return PluginInterface
     */
    public function getPlugin() : PluginInterface;


    /**
     * @param PluginInterface $plugin
     *
     * @return static
     */
    public function withPlugin(PluginInterface $plugin)/*: static*/ ;
}
