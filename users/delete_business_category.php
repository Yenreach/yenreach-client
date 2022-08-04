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
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    $gurl = "delete_business_category_api.php?string=".$string;
    $del_category = perform_get_curl($gurl);
    if($del_category){
        $return_array = $del_category;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'Category Link Broken';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>