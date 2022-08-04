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
    $gurl = "delete_review_api.php?string=".$string;
    $reviews = perform_get_curl($gurl);
    if($reviews){
        if($reviews->status == "success"){
            $review = $reviews->data;
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => "admin",
                    'agent_string' => $session->verify_string,
                    'object_type' => "BusinessReviews",
                    'object_string' => $review->verify_string,
                    "activity" => "Delete",
                    "details" => "Review deleted successfully"
                ];
            perform_post_curl($lpurl, $lpdata);
            $session->message("Review Deleted successfully");
            redirect_to("business?".$review->business_string);
        } else {
            die($reviews->message);
        }
    } else {
        die("Review Delete Link Broken");
    }
?>