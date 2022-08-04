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
    $gurl = "delete_business_video_link_api.php?string=".$string;
    $links = perform_get_curl($gurl);
    if($links){
        if($links->status == "success"){
            $link = $links->data;
            
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => "user",
                    'agent_string' => $session->verify_string,
                    'object_type' => "Businesses",
                    'object_string' => $link->business_string,
                    "activity" => "Update",
                    "details" => "Business Video Deleted successfully"
                ];
            perform_post_curl($lpurl, $lpdata);
            $session->message("Video Deleted successfully");
            redirect_to("business?".$link->business_string);
        } else {
            die($links->message);
        }
    } else {
        die("Video Link Broken");
    }
    
?>