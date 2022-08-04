<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $business_string = !empty($_GET['business']) ? (string)$_GET['business'] : "";
    $plan_string = !empty($_GET['package']) ? (string)$_GET['package'] : "";
    
    $purl = "admin_subscribe_business_api.php";
    $pdata = [
            'business_string' => $business_string,
            'payment_plan' => $plan_string,
            'user_string' => $session->verify_string
        ];
    $subscribe = perform_post_curl($purl, $pdata);
    if($subscribe){
        if($subscribe->status == "success"){
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => "admin",
                    'agent_string' => $session->verify_string,
                    'object_type' => "Businesses",
                    'object_string' => $subscribe->business_string,
                    "activity" => "Subscribing to Business",
                    "details" => "Business subscribed to {$subscribe->data->subscription} Subscription Package"
                ];
            perform_post_curl($lpurl, $lpdata);
            $session->message("Businesse was subscribed to the {$subscribe->data->subscription} Subscription Package successfully");
            redirect_to("business?{$business_string}");
        } else {
            die($subscribe->message);
        }
    } else {
        die("Business Subscription Link Broken");
    }
?>