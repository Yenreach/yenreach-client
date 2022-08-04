<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "delete_business_subscription_api.php?string=".$string;
    $delete_subscription = perform_get_curl($gurl);
    if($delete_subscription){
        if($delete_subscription->status == "success"){
            $subscription = $delete_subscription->data;
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "BusinessSubscriptions",
                    'object_string' => $subscription->verify_string,
                    "activity" => "Delete",
                    "details" => "{$subscription->package} Subscription Package deleted successfully!"
                ];
                        
            perform_post_curl($lpurl, $lpdata);
            
            $session->message("Subscription Package Deleted successfully");
            redirect_to("business_subscriptions");
        } else {
            die($delete_subscription->message);
        }
    } else {
        die("Subscription Delete Link Broken");
    }
?>