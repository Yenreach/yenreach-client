<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $return_array = array();
    
    $business_string = !empty($_GET['business_string']) ? (string)$_GET['business_string'] : "";
    $category = !empty($_GET['category']) ? (string)$_GET['category'] : "";
    $purl = "add_business_category_api.php";
    $pdata = [
            'category' => $category,
            'business_string' => $business_string
        ];
    $add_category = perform_post_curl($purl, $pdata);
    if($add_category){
        if($add_category->status == "success"){
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "Businesses",
                    'object_string' => $business_string,
                    "activity" => "Update",
                    "details" => "Category {$category} added to the Business"
                ];
            perform_post_curl($lpurl, $lpdata);   
        }
        $return_array = $add_category;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = "Business Category Addition Link Broken";
    }
    
    $result = json_encode($return_array);
    echo $result;
?>