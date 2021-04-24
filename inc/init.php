<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once (CLASS_DIR.'file.php');
include_once (CLASS_DIR.'pms.php');
if (session_status() === PHP_SESSION_NONE)
    session_start();

spl_autoload_register(function($class_name){    
        $path=  CLASS_DIR;   
        if (file_exists($path.$class_name.".php"))
        include_once $path.$class_name.".php";
    });
$pms=new pms();

?>