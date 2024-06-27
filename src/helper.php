<?php
/**
 * Return the passed field's error if exists
 */
function error($field) {
    return isset($_SESSION["error"][$field]) ? $_SESSION["error"][$field]: "";
}

/**
 * Return the passed field's old value if exists
 */
function old($field) {
    return isset($_SESSION["old"][$field]) ? $_SESSION["old"][$field] : "";
}

/**
 * Return a secure version of the passed string
 */
function escape($data) {
    return stripslashes(trim(htmlspecialchars($data)));
}

/**
 * Return the passed string without unkown characters
 */
function slugify($str) {
    // replace non letter or digits by -
    $str = preg_replace('~[^\pL\d]+~u', '-', $str);
    // transliterate
    $str = iconv('utf-8', 'us-ascii//TRANSLIT', $str);
    // remove unwanted characters
    $str = preg_replace('~[^-\w]+~', '', $str);
    // trim
    $str = trim($str, '-');
    // remove duplicate -
    $str = preg_replace('~-+~', '-', $str);
    // lowercase
    $str = strtolower($str);
    if (empty($str)) {
        return 'n-a';
    }
    return $str;
}
