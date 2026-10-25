<?php
error_reporting(E_ALL ^ E_DEPRECATED);
//error_reporting(0);

// MySQL-to-MySQLi compatibility shim for PHP 8 migration (temporary)
require_once __DIR__.'/../common/lib/mysql-shim.php';

function __cstmd_autoload($class_name) {
    $file_name=__DIR__.'/../common/'.$class_name.'.php';
	if(is_file($file_name)) require_once $file_name;
}
spl_autoload_register('__cstmd_autoload');
require_once __DIR__.'/../common/lib/recaptcha/src/ReCaptcha/ReCaptcha.php';
require_once __DIR__.'/../common/lib/recaptcha/src/ReCaptcha/RequestMethod.php';
require_once __DIR__.'/../common/lib/recaptcha/src/ReCaptcha/RequestParameters.php';
require_once __DIR__.'/../common/lib/recaptcha/src/ReCaptcha/Response.php';            
require_once __DIR__.'/../common/lib/recaptcha/src/ReCaptcha/RequestMethod/Post.php';
require_once __DIR__.'/../common/lib/recaptcha/src/ReCaptcha/RequestMethod/Socket.php';
require_once __DIR__.'/../common/lib/recaptcha/src/ReCaptcha/RequestMethod/SocketPost.php';           
?>
