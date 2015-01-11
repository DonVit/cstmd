<?php
function __autoload($class_name) {
    require_once '../common/'.$class_name . '.php';
}
?>