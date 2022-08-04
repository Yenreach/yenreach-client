<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $return_array = array();
    
    $string = !empty($_GET['business_string']) ? (string)$_GET['business_string'] : "";
    $gurl = "fetch_business_categories_api.php?string=".$string;
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