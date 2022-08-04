<?php
    require_once("../includes_public/initialize.php");
    $return_array = array();
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    $gurl = "fetch_business_review_summary_api.php?string=".$string;
    
    $reviews = perform_get_curl($gurl);
    if($reviews){
        $return_array = $reviews;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'No Review was fetched';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>