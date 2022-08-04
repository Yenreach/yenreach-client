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
    
    $gurl = "fetch_business_categories_api.php?string=".$session->business_string;
    $categories = perform_get_curl($gurl);
    if($categories){
        $return_array = $categories;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'Business Categories Link Broken';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>