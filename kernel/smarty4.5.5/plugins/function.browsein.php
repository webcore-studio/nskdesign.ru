<?php

/**
 * Smarty {browsein} plugin.
 *
 * @param array                    $params   Parameters from template.
 * @param Smarty_Internal_Template $template Smarty template object.
 *
 * @return string
 */
function smarty_function_browsein($params, $template = null)
{
    $date = isset($params['date']) && is_array($params['date']) ? $params['date'] : array();
    $delimiter = isset($params['delimiter']) ? $params['delimiter'] : '&nbsp;<span style="font-weight:bold;">&raquo;</span>&nbsp;';
    $a_attr = isset($params['a_attr']) ? trim((string) $params['a_attr']) : '';
    $show_last = isset($params['show_last']) ? (bool) $params['show_last'] : true;
    $href_last = isset($params['href_last']) ? (bool) $params['href_last'] : false;
    $lastname_only = isset($params['lastname_only']) ? (bool) $params['lastname_only'] : false;

    if (!$show_last && !empty($date)) {
        array_pop($date);
    }

    if ($lastname_only) {
        if (empty($date)) {
            return '';
        }

        $last_item = array_pop($date);
        return isset($last_item['displayname']) ? (string) $last_item['displayname'] : '';
    }

    $parts = array();
    $lastIndex = count($date) - 1;

    foreach ($date as $index => $item) {
        $displayName = isset($item['displayname']) ? (string) $item['displayname'] : '';

        if ($displayName === '') {
            continue;
        }

        $isLast = ($index === $lastIndex);

        if ($isLast && !$href_last) {
            $parts[] = $displayName;
            continue;
        }

        $url = isset($item['url']) ? (string) $item['url'] : '';
        if ($url === '') {
            $parts[] = $displayName;
            continue;
        }

        $attr = $a_attr !== '' ? ' ' . $a_attr : '';
        $parts[] = '<a' . $attr . ' href="' . $url . '">' . $displayName . '</a>';
    }

    return implode($delimiter, $parts);
}

?>
