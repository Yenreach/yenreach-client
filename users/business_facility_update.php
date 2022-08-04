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
    
    $facilities = !empty($_POST['facilities']) ? (string)$_POST['facilities'] : "";
    $purl = "business_facility_update_api.php";
    $pdata = [
            'business_string' => $session->business_string,
            'facilities' => $facilities
        ];
    
    $update_facilities = perform_post_curl($purl, $pdata);
    if($update_facilities){
        $lpurl = "add_activity_log_api.php";
        $lpdata = [
                'agent_type' => "user",
                'agent_string' => $session->verify_string,
                'object_type' => "Businesses",
                'object_string' => $session->business_string,
                "activity" => "Update",
                "details" => "Business Facilities was updated successfully"
            ];
        perform_post_curl($lpurl, $lpdata);
        $return_array = $update_facilities;
    } else {
        $return_array['status'] = 'failed';
        $return_array['message'] = 'Facilities Update Link Broken';
    }
    
    $result = json_encode($return_array);
    echo $result;
?>