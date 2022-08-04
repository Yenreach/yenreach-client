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

    $verify_string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "add_business_of_the_week_api.php?string=".$verify_string;
    $makes = perform_get_curl($gurl);
    if($makes){
        if($makes->status == "success"){
            $business = $makes->data;
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "Businesses",
                    'object_string' => $verify_string,
                    "activity" => "Business of the Week",
                    "details" => "{$business->name} was made Business of the Week"
                ];
                            
            perform_post_curl($lpurl, $lpdata);
            $session->message("Operation sucessful!");
            redirect_to("business?{$verify_string}");
        } else {
            die($makes->message);
        }
    } else {
        die("Link Broken");
    }
?>