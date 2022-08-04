<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "approve_billboard_application_api.php?string=".$string;
    $approveds = perform_get_curl($gurl);
    if($approveds){
        if($approveds->status == "success"){
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "BillboardApplications",
                    'object_string' => $approveds->data->verify_string,
                    "activity" => "Billboard Application Approvd",
                    "details" => "Billboard Application was approved"
                ];
                            
            perform_post_curl($lpurl, $lpdata);
            $session->message("Application Approved");
            redirect_to("billboard_applications_pending");
        } else {
            $session->message($approveds->message);
            redirect_to("billboard_application_pending?{$string}");
        }
    } else {
        $session->message("Approval Link Broken");
        redirect_to("billboard_application_pending?{$string}");
    }
?>