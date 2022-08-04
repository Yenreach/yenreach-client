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
    $gurl = "delete_payment_plan_api.php?string=".$string;
    $delete_plan = perform_get_curl($gurl);
    if($delete_plan){
        if($delete_plan->status == "success"){
            $plan = $delete_plan->data;
            
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "PaymentPlans",
                    'object_string' => $plan->verify_string,
                    "activity" => "Delete",
                    "details" => "{$plan->plan} being Updated"
                ];
                                
            perform_post_curl($lpurl, $lpdata);
            
            $session->message("Payment Plan Deleted successfully");
            redirect_to("subscription?".$plan->subscription_string);
        } else {
            die($delete_plan->message);
        }
    } else {
        die("Payment Plan Delete Link Broken");
    }
?>