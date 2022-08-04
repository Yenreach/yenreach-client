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
    
    $string = !empty($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "delete_business_working_hours_api.php?string=".$string;
    $delete_hours = perform_get_curl($gurl);
    if($delete_hours){
        if($delete_hours->status == "success"){
            $hour = $delete_hours->data;
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => "admin",
                    'agent_string' => $session->verify_string,
                    'object_type' => "Businesses",
                    'object_string' => $hour->business_string,
                    "activity" => "Update",
                    "details" => "Business Working Hour Deleted"
                ];
            perform_post_curl($lpurl, $lpdata);
            $session->message("Working Hour deleted successfully");
            redirect_to("business?".$hour->business_string);
        } else {
            die($delete_hours->message);
        }
    } else {
        die("Workin Hours Delete Link Broken");
    }
?>