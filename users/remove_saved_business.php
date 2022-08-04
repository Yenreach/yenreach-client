<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("users/logout");
    }
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $business_string = !empty($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "remove_saved_business_api.php?user_string=".$session->verify_string."&business_string=".$business_string;
    $removeds = perform_get_curl($gurl);
    if($removeds){
        if($removeds->status == "success"){
            $session->message("Business removed from Favourites");
            redirect_to("saved_businesses");
        } else {
            die($removeds->message);
        }
    } else {
        die("Saved Business Removal Link Broken");
    }
?>