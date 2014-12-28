<?php
function __autoload($class_name) {
    require_once 'common/'.$class_name . '.php';
    require_once 'common/lib/recaptcha-php-1.11/recaptchalib.php';
}
?>