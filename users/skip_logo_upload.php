<?php
    require_once("../../includes_public/initialize.php");
    
    $purl = "update_business_api_level3_api.php";
    $pdata = [
            'business_string' => $session->business_string,
            'upload' => "no"
        ];
    $complete_registration = perform_post_curl($purl, $pdata);
    if($complete_registration){
        if($complete_registration->status == "success"){
            $session->message("Business has been registered succesfully");
            redirect_to("business_profile");
        } else {
            $session->$complete_registration->message;
            redirect_to("add_business_comp");
        }
    } else {
        $session->message("Business Registration Completion Link Broken");
        redirect_to("add_business_comp");
    }
?>