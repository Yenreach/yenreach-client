<?php
    require_once("../../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    if(!$session->is_logged_in()){
        redirect_to("auth?page={$url}");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "delete_billboard_application_api.php?string={$string}";
    $applications = perform_get_curl($gurl);
    if($applications){
        if($applications->status == "success"){
            $application = $applications->data;
            if(file_exists("../images/{$application->filename}.jpg")){
                $photo = new Photo();
                $photo->destroy($application->filename);
            }
            $session->message("Application deleted successfully");
            redirect_to("billboard_applications");
        } else {
            die($applications->message);
        }
    } else {
        die("Billboard Applications Link Broken");
    }
?>