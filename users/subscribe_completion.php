<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("auth");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    if(!$session->is_business_logged()){
        redirect_to("dashboard");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    
    $purl = "initiate_subscription_payment_api.php";
    $pdata = [
            'user_type' => $session->user_type,
            'user_string' => $session->verify_string,
            'business_string' => $session->business_string,
            'paymentplan_string' => $string
        ];
    $initiate_subscription = perform_post_curl($purl, $pdata);
    if($initiate_subscription){
        if($initiate_subscription->status == "success"){
            $initiate = $initiate_subscription->data;
            
            redirect_to("https://payments.yenreach.com/make_payment?platform=Flutterwave&user_type={$initiate->user_type}&user_string={$initiate->user_string}&reason=business_subscription&subject={$initiate->verify_string}");
        } else {
            die($initiate_subscription->message);
            //$session->message($initiate_subscription->message);
            //redirect_to("subscription_packages");
        }
    } else {
        //$session->message("Subscription Initiation Link Broken");
        //redirect_to("subscription_packages");
        die("Subscription Initiation Link Broken");
    }
?>