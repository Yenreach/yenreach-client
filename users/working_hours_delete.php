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
    
    $string = !empty($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "delete_business_working_hours_api.php?string=".$string;
    $delete_hours = perform_get_curl($gurl);
    if($delete_hours){
        if($delete_hours->status == "success"){
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => "user",
                    'agent_string' => $session->verify_string,
                    'object_type' => "Businesses",
                    'object_string' => $session->business_string,
                    "activity" => "Update",
                    "details" => "Business Working Hour Deleted"
                ];
            perform_post_curl($lpurl, $lpdata);
            $session->message("Working Hour deleted successfully");
            redirect_to("working_hours");
        } else {
            die($delete_hours->message);
        }
    } else {
        die("Workin Hours Delete Link Broken");
    }
?>