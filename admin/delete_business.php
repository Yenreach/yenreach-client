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
    $gurl = "delete_business_api.php?string=".$verify_string;
    $dels = perform_get_curl($gurl);
    if($dels){
        if($dels->status == "success"){
            $del = $dels->data;
            
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "Businesses",
                    'object_string' => $del->verify_string,
                    "activity" => "Deleted",
                    "details" => "{$bus->name} deleted by Admin"
                ];
                            
            perform_post_curl($lpurl, $lpdata);
            
            $session->message("Business deleted successfully");
            redirect_to("all_businesses");
        } else {
            die($dels->message);
        }
    } else {
        die("Business Delete Link Broken");
    }
?>