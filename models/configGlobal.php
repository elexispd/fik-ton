<?php
// the commented part is the original downloaded code
// require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php"); 

//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url,'localhost')) {
    //require_once(__DIR__ ."\../../vendor/samayo/bulletproof/src/bulletproof.php");
    require_once(__DIR__ . "/../vendor/autoload.php");
} else if (strpos($url,'gaijinmall')) {
    //require_once($_SERVER['DOCUMENT_ROOT']."/vendor/samayo/bulletproof/src/bulletproof.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
else if (strpos($url,'192.168')) {
    //require_once(__DIR__ ."\../../vendor/samayo/bulletproof/src/bulletproof.php");
    require_once(__DIR__ . "\../vendor/autoload.php");
}
else{
    //require_once($_SERVER['DOCUMENT_ROOT']."/vendor/samayo/bulletproof/src/bulletproof.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}

//ERROR CODES
/* 
Success=1
Failed=0
Empty/Blank=404
System Error=500
Data Field Error=301
*/  
    //online 
   // define('DB_OPTIONS', 
   //  [
   //  "gaijinma_service_usr",
   //      "QpX.Y@-F6q)2",
   //      "gaijinma_services",
   //      "localhost",
   //  ], false);

    //local
    define('DB_OPTIONS', 
    [
    "root",
        "",
        "gaijinma_services",
        "localhost",
    ], false);


    define('CURRENCY',"¥");

    define ('PDO_OPTIONS', [
        PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
    ],
    false) ;
    define ('SMTP_OPTIONS', [
        "smtpnoreply@credebit.com.ng",
        "j3W(ix)1=D~v",
        "localhost"
    ],
    false) ;

    define ('SMS_OPTIONS', [
        "https://sfsfdsfdsfnfdsfdsf/comfsfs",
    ],
    false);

    define('POINT_URL_BACK','./');
    define('MALL_ROOT','https://gaijinmall.com/');

?>