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
    
    $return_array = array();
    
    $facility_string = !empty($_GET['facility_string']) ? (string)$_GET['facility_string'] : "";
    $gurl = "business_facility_activation_api.php?business=".$session->business_string."&facility=".$facility_string;
    $facility_add = perform_get_curl($gurl);
    if($facility_add){
        
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'Facility Activation Link Broken';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>