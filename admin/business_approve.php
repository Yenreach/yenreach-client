<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to('logout.php');
    }
    if($session->user_type != "admin"){
        redirect_to("logout.php");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "approve_business_api.php?string=".$string;
    $approve_business = perform_get_curl($gurl);
    if($approve_business){
        if($approve_business->status == "success"){
            $business = $approve_business->data;
            
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "Businesses",
                    'object_string' => $business->verify_string,
                    "activity" => "Business Approval",
                    "details" => "{$business->name} was approved"
                ];
                            
            perform_post_curl($lpurl, $lpdata);
            $session->message("Business approved successfully");
            redirect_to("pending_businesses");
        } else {
            die($approve_business->message);
        }
    } else {
        die("Business approval Link Broken");
    }
?>