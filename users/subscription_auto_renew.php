<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("auth");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    if(!$session->is_business_logged()){
        redirect_to("dasboard");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    $string = !empty($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_business_subscription_api.php?string=".$string;
    $bus_subscriptions = perform_get_curl($gurl);
    if($bus_subscriptions){
        if($bus_subscriptions->status == "success"){
            $subscription = $bus_subscriptions->data;
            if(($subscription->business_string == $session->business_string) && ($subscription->user_string == $session->verify_string)){
                $agurl = "subscription_auto_renew_api.php?string=".$subscription->verify_string;
                $auto_renew = perform_get_curl($agurl);
                if($auto_renew){
                    if($auto_renew->status == "success"){
                        $renew = $auto_renew->data;
                        
                        if($renew->auto_renew == 1){
                            $action = "Subscripton Auto Renewal Enabled";
                        } else {
                            $action = "Subscription Auto Renewal Disabled";
                        }
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => "user",
                                'agent_string' => $session->verify_string,
                                'object_type' => "Subscribers",
                                'object_string' => $renew->verify_string,
                                "activity" => "Update",
                                "details" => $action
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        $session->message($action);
                        redirect_to("subscription?{$renew->verify_string}");
                    } else {
                        die($auto_renew->message);
                    }
                } else {
                    die("Subscription Auto Renewal Link Broken");
                }
            } else {
                die("Wrong Permissions");
            }
        } else {
            die($bus_subscriptions->message);
        }
    } else {
        die("Subscription Link Broken");
    }
?>