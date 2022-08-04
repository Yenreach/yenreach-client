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
    $purl = "mark_billboard_application_as_paid_api.php";
    $pdata = [
            "verify_string" => $string,
            "agent_type" => $session->user_type,
            "agent_string" => $session->verify_string
        ];
    $mark = perform_post_curl($purl, $pdata);
    if($mark){
        if($mark->status == "success"){
            $session->message("Operation Successful");
        } else {
            die("11111".$mark->message);
        }
    } else {
        die("Error! Link Broken!");
    }
    
    redirect_to("billboard_application?{$string}");
?>