<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("auth");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    $gurl = "remove_user_profile_photo_api.php?string=".$session->verify_string;
    $users = perform_get_curl($gurl);
    if($users){
        if($users->status == "success"){
            $user = $users->data;
            
            $filepath = $user->filepath;
            if(file_exists("../".$filepath)){
                $photo = new Photo();
                $photo->destroy_path($filepath);
            }
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => "user",
                    'agent_string' => $session->verify_string,
                    'object_type' => "Users",
                    'object_string' => $session->verify_string,
                    "activity" => "Update",
                    "details" => "Profile Photo was removed"
                ];
            perform_post_curl($lpurl, $lpdata);
            $session->message("Profile Photo removed successfully");
            redirect_to("user_profile");
        } else {
            die($users->message);
        }
    } else {
        die("Profile Photo Removal Link Broken");
    }
?>