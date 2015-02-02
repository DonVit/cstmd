<?php
error_reporting(0); 
$commonpath='../common/';
function __autoload($class_name) {
	global $commonpath;
    require_once $commonpath.$class_name . '.php';
    require_once '../common/lib/recaptcha-php-2.00/recaptchalib.php';
}
?>