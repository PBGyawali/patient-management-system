<?php
    /* Database credentials.  */
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_NAME', 'patient_management_system');
    if (session_status() === PHP_SESSION_NONE)
    session_start();

    date_default_timezone_set("Europe/Berlin");
	 // define global constants      
    define("BASE_URL", "http://localhost/pms/"); //defines the base url to start from in the server 
    define("LOGIN_URL", "http://localhost/pms/index.php"); //defines the base url to start from in the server  
    define("DASHBOARD_URL", "http://localhost/pms/dashboard.php");
    define("SERVER_URL", BASE_URL."");    
    define("ASSETS_URL", BASE_URL."");
    define("CSS_URL", ASSETS_URL."css/");
    define("JS_URL", ASSETS_URL."js/");
    define("IMAGES_URL", ASSETS_URL."images/");   
    define("FONTS_URL", ASSETS_URL."font/");  

    define ("ROOT_PATH", realpath(dirname(__FILE__))); //starts the path from where the config.php file is located
    define ("BASE_PATH",ROOT_PATH."/");
    define("ERROR_LOG",BASE_PATH."errorlog/error.log");    
    define("INC", BASE_PATH."inc/"); 
    define("CLASS_DIR", BASE_PATH."class/"); 
    define("SERVER", BASE_PATH."server/"); 
    define("ASSETS_DIR", BASE_PATH."");
    define("CSS_DIR", ASSETS_DIR."css/");
    define("JS_DIR", ASSETS_DIR."js/");
    define("IMAGES_DIR", ASSETS_DIR."images/");
    define("FONTS_DIR", ASSETS_DIR."font/");	

    define("ALLOWED_IMAGES", array("jpg", "jpeg", "png"));    

    define("SITE_NAME", "Patient Management System");
   
    $page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);


?>