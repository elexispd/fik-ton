    <?php 
//Confirm if file is local or Public and add the right path
// session_start();
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
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

    $acntManager = new AccountManager();
 

    $cart_ob = new CartManager();

    if (isset($_POST["addToCart"])) {
        $result = $cart_ob->addToCart($_POST["user_id"], $_POST["vendor"], $_POST["ad_id"], $_POST["title"], $_POST["cat"], $_POST["price"],$_POST["vendor_price"], 1);
        echo($result);
    }

    if (isset($_POST["item"])) {
        $remove = $cart_ob->removeFromCart($_POST["user_id"], $_POST["ad_id"]);
        print_r($remove);
    }
    

    if(isset($_POST['order'])) {

        $address = htmlspecialchars($_POST['address']);
        $delivery = htmlspecialchars($_POST['delivery_method']);
        $total = htmlspecialchars($_POST['total']);
        $term = htmlspecialchars($_POST['term']);
        $user_id = htmlspecialchars($_POST['user_id']);
        $shipping = htmlspecialchars($_POST['shipping_cost']);

        $order = $cart_ob->setOrder($p_method = null, $delivery, $shipping, $address, $term, $total, $user_id);

        print_r($order);   
      
    }

    if (isset($_POST['quantity'])) {
        $result = $cart_ob->updateCart(htmlspecialchars($_POST["u_id"]), htmlspecialchars($_POST["a_id"]), htmlspecialchars($_POST["new_price"]),  htmlspecialchars($_POST["comm"]), htmlspecialchars($_POST["quantity"]));
        print_r($_POST);
    }

    // print_r($_POST);
    
    if (isset($_POST["order_id"]) && isset($_POST["tracking"]) && isset($_POST["user_id"])) {
        $result = $cart_ob->updateTrackingID(htmlspecialchars($_POST["tracking"]), htmlspecialchars($_POST["order_id"]), htmlspecialchars($_POST["user_id"]));
        print_r($result);
    }

    if (isset($_POST["reason"]) && isset($_POST["order_id"])) {
        $result = $cart_ob->askForReFund(htmlspecialchars($_POST["order_id"]), htmlspecialchars($_POST['user_id']), htmlspecialchars($_POST["reason"]));
        print_r($result);
    }
    if (isset($_POST["cancel_order"]) && isset($_POST["order_id"])) {
        $result = $cart_ob->cancelOrder(htmlspecialchars($_POST["order_id"]), htmlspecialchars($_POST['user_id']));
        print_r($result);
        // print_r($_POST);
    }
    if (isset($_POST["refund_order"]) && isset($_POST['user_id'])) {
        $result = $cart_ob->refundPayment(htmlspecialchars($_POST["order_id"]), htmlspecialchars($_POST['user_id']));
        print_r($result);
    }
    if (isset($_POST["complete_order"]) && $_POST['user_id']) {
        $result = $cart_ob->completeOrder(htmlspecialchars($_POST["order_id"]), htmlspecialchars($_POST['user_id']));
        print_r($result);
    }




