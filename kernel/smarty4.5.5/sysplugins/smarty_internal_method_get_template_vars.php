<?php

require_once __DIR__ . '/smarty_internal_method_gettemplatevars.php';

/**
 * Backward compatibility wrapper for getTemplateVars method extension class.
 *
 * Some call sites resolve to Smarty_Internal_Method_Get_Template_Vars while
 * the implementation class is Smarty_Internal_Method_GetTemplateVars.
 */
class Smarty_Internal_Method_Get_Template_Vars extends Smarty_Internal_Method_GetTemplateVars
{
    /**
     * Legacy snake_case entrypoint used by older extension handler calls.
     *
     * @param \Smarty_Internal_Data|\Smarty_Internal_Template|\Smarty      $data
     * @param string                                                       $varName
     * @param \Smarty_Internal_Data|\Smarty_Internal_Template|\Smarty|null $_ptr
     * @param bool                                                         $searchParents
     *
     * @return mixed
     */
    public function get_template_vars(
        Smarty_Internal_Data $data,
        $varName = null,
        ?Smarty_Internal_Data $_ptr = null,
        $searchParents = true
    ) {
        return $this->getTemplateVars($data, $varName, $_ptr, $searchParents);
    }
}
