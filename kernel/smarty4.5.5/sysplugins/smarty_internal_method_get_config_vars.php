<?php

/**
 * Backward-compatibility alias for legacy snake_case get_config_vars method mapping.
 *
 * @package Smarty
 * @subpackage PluginsInternal
 */
class Smarty_Internal_Method_Get_Config_Vars extends Smarty_Internal_Method_GetConfigVars
{
    /**
     * Backward-compatible snake_case wrapper for legacy codebases.
     *
     * @param \Smarty_Internal_Data|\Smarty_Internal_Template|\Smarty $data
     * @param string|null                                             $varname
     * @param bool                                                    $search_parents
     *
     * @return mixed
     */
    public function get_config_vars(Smarty_Internal_Data $data, $varname = null, $search_parents = true)
    {
        return $this->getConfigVars($data, $varname, $search_parents);
    }
}
