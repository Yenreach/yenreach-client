<?php
    require_once("../../includes_public/initialize.php");
    $tx_ref = !empty($_GET['tx_ref']) ? (string)$_GET['tx_ref'] : "";
    if(empty($tx_ref)){
        $session->message('Payment Failure!');
        redirect_to('payment_failure.php');
    }
    $transaction_id = !empty($_GET['transaction_id']) ? (string)$_GET['transaction_id'] : "";
    
    $get_array = $_GET;
    $get_json = json_encode($_GET);
    
    $purl = "flutterwave_verify_transaction_api.php";
    $pdata = [
            'txref' => $tx_ref,
            'tranx_id' => $transaction_id,
            'data' => $get_json
        ];
    $verify = perform_post_curl($purl, $pdata);
    if($verify){
        if($verify->status == 'success'){
            $verified = $verify->data;
            if($verified->reason == "business_subscription"){
                redirect_to("https://yenreach.com/users/business_subscription?string={$verified->verify_string}&method=online_payment");
            } elseif($verified->reason == "business_subscription_renewal"){
                redirect_to("https://yenreach.com/users/business_subscription_renewal?string={$verified->verify_string}&method=online_payment");
            } elseif($verified->reason == "billboard_payment"){
                redirect_to("https://yenreach.com/users/billboard_apply?string={$verified->verify_string}&method=online_payment");
            } else {
                $session->message("No Payment Reason was provided");
                redirect_to("failure.php");
            }
        } else {
            $session->message($verify->message);
            redirect_to("failure.php");
        }
    } else {
        $message = "Payment Verification Link was Broken. If you have been debited, please send a mail to <a href=\"mailto:techsupport@hollotechservices.com\">techsupprt@hollotechservices.com</a>";
        $session->message($message);
        redirect_to("failure.php");
    }
?>