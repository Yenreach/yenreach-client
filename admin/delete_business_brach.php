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
    $gurl = "delete_branch_api.php?string=".$string;
    $branches = perform_get_curl($gurl);
    if($branches){
        if($branches->status == "success"){
            $branch = $branches->data;
            
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => "admin",
                    'agent_string' => $session->verify_string,
                    'object_type' => "Branches",
                    'object_string' => $branch->verify_string,
                    "activity" => "Delete",
                    "details" => "Branch Deleted successfully"
                ];
            perform_post_curl($lpurl, $lpdata);
            $session->message("Branch added successfully");
            redirect_to("business?".$branch->business_string);
        } else {
            die($branches->message);
        }
    } else {
        die("Branch Delete Link Broken");
    }
?>