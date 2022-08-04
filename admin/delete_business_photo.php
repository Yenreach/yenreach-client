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
    $gurl = "delete_business_photo_api.php?string=".$string;
    $photos = perform_get_curl($gurl);
    if($photos){
        if($photos->status == "success"){
            $picture = $photos->data;
            
            $photo = new Photo();
            if(file_exists("../images/{$picture->filename}.jpg")){
                $photo->destroy($picture->filename);
            }
            if(file_exists("../images/{$picture->filename}.jpg")){
                $photo->destroy_thumbnail($picture->filename);
            }
            
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => "admin",
                    'agent_string' => $session->verify_string,
                    'object_type' => "Businesses",
                    'object_string' => $picture->business_string,
                    "activity" => "Update",
                    "details" => "Business Photo was deleted"
                ];
            perform_post_curl($lpurl, $lpdata);
            $session->message("Photo Deleted successfully");
            redirect_to("business?".$picture->business_string);
        } else {
            die($photos->message);
        }
    } else {
        die("Delete Photo Link Broken");
    }
?>