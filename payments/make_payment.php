<?php
    require_once("../../includes_public/initialize.php");
    
    $platform = !empty($_GET['platform']) ? (string)$_GET['platform'] : "";
    $user_type = !empty($_GET['user_type']) ? (string)$_GET['user_type'] : "";
    $user_string = !empty($_GET['user_string']) ? (string)$_GET['user_string'] : "";
    $reason = !empty($_GET['reason']) ? (string)$_GET['reason'] : "";
    $subject = !empty($_GET['subject']) ? (string)$_GET['subject'] : "";
    
    $purl = "initiate_payments_api.php";
    $pdata = [
            'platform' => $platform,
            'user_type' => $user_type,
            'user_string' => $user_string,
            'reason' => $reason,
            'subject' => $subject
        ];
    
    $initiate_payment = perform_post_curl($purl, $pdata);
    if($initiate_payment){
        if($initiate_payment->status == "success"){
            $initiate = $initiate_payment->data;
            $link = $initiate->url;
            redirect_to($link);
        } else {
            $message = "Payment Initiation failed for the following reason(s)";
            $message .= $initiate_payment->message;
            $session->message($message);
        }
    } else {
        $session->message("Payment Initiation Link Broken");
        redirect_to("payment_failure.php");
    }
 ?>