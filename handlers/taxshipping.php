<?php 
//Confirm if file is local or Public and add the right path
//session_start();
//  error_reporting(0);
// ini_set('display_errors', 1); 
//  ini_set('display_startup_errors', 1);
//  error_reporting(E_ALL);
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url, 'localhost')) {
    require_once(__DIR__ . "/../vendor/autoload.php");
} else if (strpos($url, 'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
} else if (strpos($url, '192.168')) {
    require_once(__DIR__ . "\../vendor/autoload.php");
} else {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
}


    use services\MedS\MediaManager;
    use services\AccS\AccountManager;
    use services\AudS\AuditManager;
    use services\MsgS\feedbackManager;
    use services\SecS\SecurityManager;
    use services\CatS\CartManager;
    USE services\AdS\AdManager;


    $mediaManager = new MediaManager();
    $adManager = new AdManager();
    $cart_ob = new CartManager();



	if (isset($_POST["tax"])) {
	   $result = $cart_ob->setTax($_POST['cat'], $_POST['taxAmount']);
	    print_r($result);
	}

	if (isset($_POST["btnShip"])) {
	   $result = $cart_ob->setShipping($_POST['state'], $_POST['shipAmount']);
	    print_r($result);
	}

    if ($_GET['state']) {
        $result = $cart_ob->getTotalShipping($_GET['state']);
        echo $result;
    }

	
