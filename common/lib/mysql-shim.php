<?php
/**
 * MySQL to MySQLi compatibility shim for PHP 8 migration.
 *
 * Provides legacy mysql_* function signatures backed by mysqli_*.
 * This is a TEMPORARY bridge to get the application booting on PHP 8.
 * The DB layer (DBConnection, DBManager, DBTreeManager) should be rewritten
 * natively with mysqli, then this shim removed.
 *
 * @deprecated Temporary migration aid only. Remove after Phase 2.
 */

// Store the default connection for functions that omit the link parameter
$GLOBALS['__mysql_shim_default_link'] = null;

/**
 * Open a connection to a MySQL Server
 *
 * @param string $server
 * @param string $username
 * @param string $password
 * @param bool $new_link
 * @param int $client_flags
 * @return mysqli|false
 */
function mysql_connect($server, $username, $password, $new_link = false, $client_flags = 0) {
    $link = mysqli_connect($server, $username, $password);
    if ($link === false) {
        return false;
    }
    $GLOBALS['__mysql_shim_default_link'] = $link;
    return $link;
}

/**
 * Select a MySQL database
 *
 * @param string $database_name
 * @param mysqli|null $link_identifier
 * @return bool
 */
function mysql_select_db($database_name, $link_identifier = null) {
    $link = $link_identifier ?: $GLOBALS['__mysql_shim_default_link'];
    return mysqli_select_db($link, $database_name);
}

/**
 * Send a MySQL query
 *
 * @param string $query
 * @param mysqli|null $link_identifier
 * @return mysqli_result|bool
 */
function mysql_query($query, $link_identifier = null) {
    $link = $link_identifier ?: $GLOBALS['__mysql_shim_default_link'];
    return mysqli_query($link, $query);
}

/**
 * Fetch a result row as an associative array
 *
 * @param mysqli_result $result
 * @return array|false
 */
function mysql_fetch_assoc($result) {
    return mysqli_fetch_assoc($result);
}

/**
 * Fetch a result row as an object
 *
 * @param mysqli_result $result
 * @param string $class_name
 * @param array $params
 * @return object|false
 */
function mysql_fetch_object($result, $class_name = 'stdClass', $params = []) {
    return mysqli_fetch_object($result, $class_name, $params);
}

/**
 * Get number of rows in result
 *
 * @param mysqli_result $result
 * @return int
 */
function mysql_num_rows($result) {
    return mysqli_num_rows($result);
}

/**
 * Get number of fields in result
 *
 * @param mysqli_result $result
 * @return int
 */
function mysql_num_fields($result) {
    return mysqli_num_fields($result);
}

/**
 * Get the name of the specified field in a result
 *
 * @param mysqli_result $result
 * @param int $field_offset
 * @return string|false
 */
function mysql_field_name($result, $field_offset) {
    $field = mysqli_fetch_field_direct($result, $field_offset);
    return $field ? $field->name : false;
}

/**
 * Get the ID generated in the last query
 *
 * @param mysqli|null $link_identifier
 * @return int|string
 */
function mysql_insert_id($link_identifier = null) {
    $link = $link_identifier ?: $GLOBALS['__mysql_shim_default_link'];
    return mysqli_insert_id($link);
}

/**
 * Escapes special characters in a string for use in an SQL statement
 *
 * @param string $unescaped_string
 * @param mysqli|null $link_identifier
 * @return string|false
 */
function mysql_real_escape_string($unescaped_string, $link_identifier = null) {
    $link = $link_identifier ?: $GLOBALS['__mysql_shim_default_link'];
    return mysqli_real_escape_string($link, $unescaped_string);
}

/**
 * Returns the text of the error message from previous MySQL operation
 *
 * @param mysqli|null $link_identifier
 * @return string
 */
function mysql_error($link_identifier = null) {
    $link = $link_identifier ?: $GLOBALS['__mysql_shim_default_link'];
    return mysqli_error($link);
}

/**
 * Close MySQL connection
 *
 * @param mysqli|null $link_identifier
 * @return bool
 */
function mysql_close($link_identifier = null) {
    $link = $link_identifier ?: $GLOBALS['__mysql_shim_default_link'];
    return mysqli_close($link);
}

/**
 * Get result data
 *
 * @param mysqli_result $result
 * @param int $row
 * @param mixed $field
 * @return mixed
 */
function mysql_result($result, $row, $field = 0) {
    mysqli_data_seek($result, $row);
    $row_data = mysqli_fetch_array($result, MYSQLI_BOTH);
    return $row_data[$field] ?? false;
}

/**
 * Sets the client character set
 *
 * @param string $charset
 * @param mysqli|null $link_identifier
 * @return bool
 */
function mysql_set_charset($charset, $link_identifier = null) {
    $link = $link_identifier ?: $GLOBALS['__mysql_shim_default_link'];
    return mysqli_set_charset($link, $charset);
}

/**
 * Escapes a string for use in a mysql_query (legacy, non-safe)
 *
 * @param string $unescaped_string
 * @return string
 * @deprecated Use mysql_real_escape_string instead
 */
function mysql_escape_string($unescaped_string) {
    return addslashes($unescaped_string);
}
