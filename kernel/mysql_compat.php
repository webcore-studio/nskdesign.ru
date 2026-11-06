<?php

/**
 * Compatibility layer for legacy mysql_* API on modern PHP.
 * Uses mysqli underneath when ext/mysql is unavailable.
 */
if (!function_exists('mysql_connect')) {
    $GLOBALS['__mysql_compat_link'] = null;
    $GLOBALS['__mysql_compat_last_error'] = '';
    $GLOBALS['__mysql_compat_last_errno'] = 0;
    $GLOBALS['__mysql_compat_last_result'] = null;

    function __mysql_compat_set_error($errno, $error)
    {
        $GLOBALS['__mysql_compat_last_errno'] = (int) $errno;
        $GLOBALS['__mysql_compat_last_error'] = (string) $error;
    }

    function __mysql_compat_get_link($link_identifier = null)
    {
        if ($link_identifier instanceof mysqli) {
            return $link_identifier;
        }
        if (isset($GLOBALS['__mysql_compat_link']) && $GLOBALS['__mysql_compat_link'] instanceof mysqli) {
            return $GLOBALS['__mysql_compat_link'];
        }
        return null;
    }

    function mysql_connect($server = null, $username = null, $password = null, $new_link = false, $client_flags = 0)
    {
        $host = $server ?: ini_get('mysqli.default_host');
        $user = $username ?: ini_get('mysqli.default_user');
        $pass = $password ?: ini_get('mysqli.default_pw');

        if (function_exists('mysqli_report')) {
            mysqli_report(MYSQLI_REPORT_OFF);
        }

        $link = @mysqli_init();
        if (!$link) {
            __mysql_compat_set_error(2000, 'mysqli_init failed');
            return false;
        }

        $ok = @mysqli_real_connect($link, $host, $user, $pass, null, null, null, $client_flags);
        if (!$ok) {
            __mysql_compat_set_error(mysqli_connect_errno(), mysqli_connect_error());
            return false;
        }

        $GLOBALS['__mysql_compat_link'] = $link;
        __mysql_compat_set_error(0, '');
        return $link;
    }

    function mysql_select_db($database_name, $link_identifier = null)
    {
        $link = __mysql_compat_get_link($link_identifier);
        if (!$link) {
            __mysql_compat_set_error(2002, 'No active connection');
            return false;
        }
        $ok = @mysqli_select_db($link, $database_name);
        __mysql_compat_set_error(mysqli_errno($link), mysqli_error($link));
        return $ok;
    }

    function mysql_query($query, $link_identifier = null)
    {
        $link = __mysql_compat_get_link($link_identifier);
        if (!$link) {
            __mysql_compat_set_error(2002, 'No active connection');
            return false;
        }
        $result = @mysqli_query($link, $query);
        $GLOBALS['__mysql_compat_last_result'] = $result;
        __mysql_compat_set_error(mysqli_errno($link), mysqli_error($link));
        return $result;
    }

    function mysql_error($link_identifier = null)
    {
        $link = __mysql_compat_get_link($link_identifier);
        if ($link) {
            $error = mysqli_error($link);
            if ($error !== '') {
                return $error;
            }
        }
        return isset($GLOBALS['__mysql_compat_last_error']) ? $GLOBALS['__mysql_compat_last_error'] : '';
    }

    function mysql_errno($link_identifier = null)
    {
        $link = __mysql_compat_get_link($link_identifier);
        if ($link) {
            $errno = mysqli_errno($link);
            if ($errno !== 0) {
                return $errno;
            }
        }
        return isset($GLOBALS['__mysql_compat_last_errno']) ? (int) $GLOBALS['__mysql_compat_last_errno'] : 0;
    }

    function mysql_set_charset($charset, $link_identifier = null)
    {
        $link = __mysql_compat_get_link($link_identifier);
        if (!$link) {
            __mysql_compat_set_error(2002, 'No active connection');
            return false;
        }
        $ok = @mysqli_set_charset($link, $charset);
        __mysql_compat_set_error(mysqli_errno($link), mysqli_error($link));
        return $ok;
    }

    function mysql_real_escape_string($unescaped_string, $link_identifier = null)
    {
        $link = __mysql_compat_get_link($link_identifier);
        if (!$link) {
            return addslashes($unescaped_string);
        }
        $escaped = mysqli_real_escape_string($link, $unescaped_string);
        __mysql_compat_set_error(mysqli_errno($link), mysqli_error($link));
        return $escaped;
    }

    function mysql_num_rows($result)
    {
        return ($result instanceof mysqli_result) ? mysqli_num_rows($result) : 0;
    }

    function mysql_fetch_assoc($result)
    {
        return ($result instanceof mysqli_result) ? mysqli_fetch_assoc($result) : false;
    }

    function mysql_fetch_row($result)
    {
        return ($result instanceof mysqli_result) ? mysqli_fetch_row($result) : false;
    }

    function mysql_insert_id($link_identifier = null)
    {
        $link = __mysql_compat_get_link($link_identifier);
        if (!$link) {
            return 0;
        }
        return mysqli_insert_id($link);
    }

    function mysql_free_result($result)
    {
        if ($result instanceof mysqli_result) {
            mysqli_free_result($result);
            return true;
        }
        return false;
    }

    function mysql_num_fields($result)
    {
        return ($result instanceof mysqli_result) ? mysqli_num_fields($result) : 0;
    }

    function mysql_field_name($result, $field_offset)
    {
        if (!($result instanceof mysqli_result)) {
            return false;
        }
        $fields = mysqli_fetch_fields($result);
        return isset($fields[$field_offset]) ? $fields[$field_offset]->name : false;
    }

    function mysql_result($result, $row, $field = 0)
    {
        if (!($result instanceof mysqli_result)) {
            return false;
        }
        if (!mysqli_data_seek($result, (int) $row)) {
            return false;
        }
        $data = mysqli_fetch_array($result, MYSQLI_BOTH);
        if ($data === null || $data === false) {
            return false;
        }
        if (is_int($field) || ctype_digit((string) $field)) {
            return isset($data[(int) $field]) ? $data[(int) $field] : false;
        }
        return isset($data[$field]) ? $data[$field] : false;
    }

    function mysql_list_fields($database_name, $table_name, $link_identifier = null)
    {
        $sql = 'SHOW COLUMNS FROM `' . str_replace('`', '``', $table_name) . '` FROM `' . str_replace('`', '``', $database_name) . '`';
        return mysql_query($sql, $link_identifier);
    }
}

/**
 * Compatibility shim for each(), removed in PHP 8.0.
 * Returns the current key/value pair and advances the array cursor.
 */
if (!function_exists('each')) {
    function each(&$array)
    {
        $key = key($array);
        if ($key === null) {
            return false;
        }
        $result = [1 => current($array), 'value' => current($array), 0 => $key, 'key' => $key];
        next($array);
        return $result;
    }
}
