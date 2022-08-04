<?php
    require_once("../../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    
    if(!$session->is_logged_in()){
        redirect_to("auth?page=".$url);   
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    $exploded = explode("?", $url);
    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_business_by_string_api.php?string=".$string;
    $businesses = perform_get_curl($gurl);
    if($businesses){
        if($businesses->status == "success"){
            $business = $businesses->data;
            
            $session->business_login($business->verify_string);
            if($business->reg_stage < 1){
                redirect_to("add_business");
            } elseif($business->reg_stage < 2){
                redirect_to("add_business_cont");
            } elseif($business->reg_stage < 3){
                redirect_to("add_business_comp");
            } else {
                redirect_to("business_profile");
            }
        } else {
            $session->message($businesses->message);
            redirect_to("dashboard");
        }
    } else {
        $session->message("Business Link Broken");
        redirect_to("dashboard");
    }
?>