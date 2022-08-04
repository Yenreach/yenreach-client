<?php
    require_once('../../includes_public/initialize.php');
    if(!$session->is_logged_in()){
        redirect_to("logout.php");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout.php");
    }
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    $gurl = "delete_category_api.php?string=".$string;
    $categorys = perform_get_curl($gurl);
    if($categorys){
        if($categorys->status == "success"){
            $category = $categorys->data;
            
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "Category",
                    'object_string' => $category->verify_string,
                    "activity" => "Delete",
                    "details" => "{$category->category} Deleted as a Category"
                ];
            perform_post_curl($lpurl, $lpdata);
            
            $session->message("{$category->category} has been deleted as a Category");
            redirect_to("categories");
        } else {
            die($categorys->message);
        }
    } else {
        die("Category Link Broken");
    }
?>