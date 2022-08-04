<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout.php");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout.php");
    }
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    $gurl = "delete_section_api.php?string=".$string;
    $sections = perform_get_curl($gurl);
    if($sections){
        if($sections->status == "success"){
            $sect = $sections->data;
            
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "Sections",
                    'object_string' => $sect->verify_string,
                    "activity" => "Delete",
                    "details" => "{$sect->section} Deleted as a Section"
                ];
            perform_post_curl($lpurl, $lpdata);
            $session->message("{$sect->section} Deleted successfully");
            redirect_to("sections");
        } else {
            die($sections->message);
        }
    } else {
        die("Section Link Broken");
    }
?>