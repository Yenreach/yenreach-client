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
    
    $gurl = "remove_business_logo_api.php?string=".$session->business_string;
    $remove_logo = perform_get_curl($gurl);
    if($remove_logo){
        if($remove_logo->status == "success"){
            $logo = $remove_logo->data;
            
            if(!empty($logo->old_filename)){
                $photo = new Photo();
                if(file_exists("../images/thumbnails/{$logo->old_filename}.jpg")){
                    $photo->destroy_thumbnail($logo->old_filename);
                }
                if(file_exists("../images/{$logo->old_filename}.jpg")){
                    $photo->destroy($logo->old_filename);
                }
            }
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => "user",
                    'agent_string' => $session->verify_string,
                    'object_type' => "Businesses",
                    'object_string' => $session->business_string,
                    "activity" => "Update",
                    "details" => "Business Logo Removed"
                ];
            perform_post_curl($lpurl, $lpdata);
            $session->message("Logo successfully removed");
        } else {
            $session->message($remove_logo->message);
        }
    } else {
        $session->message("Logo Removal Link Broken");
    }
    
    redirect_to("business_profile");
?>