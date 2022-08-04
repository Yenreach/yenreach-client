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
    $gurl = "delete_advert_payment_type_api.php?string=".$string;
    $adverts = perform_get_curl($gurl);
    if($adverts){
        if($adverts->status == "success"){
            $advert = $adverts->data;
            
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "AdvertPayemntTypes",
                    'object_string' => $advert->verify_string,
                    "activity" => "Delete",
                    "details" => "{$title} deleted as an Advert Payment Type"
                ];
            perform_post_curl($lpurl, $lpdata);
            
            $session->message($advert->title." deleted successfully");
            redirect_to("advert_payment_types");
        } else {
            die($adverts->message);
        }
    } else {
        die("Delete Link Broken");
    }
?>