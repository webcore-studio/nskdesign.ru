<?php

/**
 * Backward-compatibility alias for legacy snake_case config_load method mapping.
 *
 * @package Smarty
 * @subpackage PluginsInternal
 */
class Smarty_Internal_Method_Config_Load extends Smarty_Internal_Method_ConfigLoad
{
    /**
     * Backward-compatible snake_case wrapper for legacy codebases.
     *
     * @param \Smarty_Internal_Data|\Smarty_Internal_Template|\Smarty $data
     * @param string                                                  $config_file
     * @param mixed                                                   $sections
     *
     * @return \Smarty|\Smarty_Internal_Data|\Smarty_Internal_Template
     * @throws \Exception
     */
    public function config_load(Smarty_Internal_Data $data, $config_file, $sections = null)
    {
        return $this->configLoad($data, $config_file, $sections);
    }
}
