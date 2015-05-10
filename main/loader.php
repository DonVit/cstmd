<?php
error_reporting(E_ALL ^ E_DEPRECATED);
function __autoload($class_name) {
    $file_name=__DIR__.'/../common/'.$class_name.'.php';
	if(is_file($file_name)) require_once $file_name;
//    require_once '../common/lib/recaptcha-php-2.00/recaptchalib.php';
}
require_once __DIR__.'/../common/lib/recaptcha/src/ReCaptcha/ReCaptcha.php';
require_once __DIR__.'/../common/lib/recaptcha/src/ReCaptcha/RequestMethod.php';
require_once __DIR__.'/../common/lib/recaptcha/src/ReCaptcha/RequestParameters.php';
require_once __DIR__.'/../common/lib/recaptcha/src/ReCaptcha/Response.php';            
require_once __DIR__.'/../common/lib/recaptcha/src/ReCaptcha/RequestMethod/Post.php';
require_once __DIR__.'/../common/lib/recaptcha/src/ReCaptcha/RequestMethod/Socket.php';
require_once __DIR__.'/../common/lib/recaptcha/src/ReCaptcha/RequestMethod/SocketPost.php';           
?>