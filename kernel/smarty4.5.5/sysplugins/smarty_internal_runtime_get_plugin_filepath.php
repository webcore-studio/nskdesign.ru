<?php

/**
 * Smarty Internal Runtime Get Plugin Filepath (legacy compatibility)
 *
 * Provides compatibility for templates compiled with older Smarty versions
 * that reference $smarty->ext->_get_plugin_filepath.
 *
 * @package    Smarty
 * @subpackage PluginsInternal
 */
class Smarty_Internal_Runtime_Get_Plugin_Filepath
{
    /**
     * Resolve and load a plugin file, returning its filepath when found.
     *
     * @param \Smarty $smarty
     * @param string  $type plugin type (function, modifier, block, etc.)
     * @param string  $name plugin name
     *
     * @return string|false
     */
    public function getPluginFilepath($data, $type, $name)
    {
        $smarty = $data instanceof Smarty ? $data : $data->smarty;
        $pluginName = sprintf('smarty_%s_%s', $type, $name);
        return $smarty->ext->loadPlugin->loadPlugin($smarty, $pluginName, false);
    }

    /**
     * Backward-compatible alias for snake_case calls.
     *
     * @param \Smarty $smarty
     * @param string  $type
     * @param string  $name
     *
     * @return string|false
     */
    public function _get_plugin_filepath($data, $type, $name)
    {
        return $this->getPluginFilepath($data, $type, $name);
    }
}
