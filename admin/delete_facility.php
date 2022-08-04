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
    $gurl = "delete_facility_api.php?string=".$string;
    $facilities = perform_get_curl($gurl);
    if($facilities){
        if($facilities->status == "success"){
            $facility = $facilities->data;
            
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "Facilities",
                    'object_string' => $update_facility->verify_string,
                    "activity" => "Delete",
                    "details" => "{$update->facility} was deleted"
                ];
            perform_post_curl($lpurl, $lpdata);
            $session->message("Facility deleted successfully");
            redirect_to("business_facilities");
        } else {
            die($facilities->message);
        }
    } else {
        die("Faility Delete Link Broken");
    }
?>