<?php
    require_once("../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("users/logout");
    }
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $exploding = !empty($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($exploding)){
        $explode = explode("/", $exploding);
        $business_string = array_shift($explode);
        $purl = "save_business_api.php";
        $pdata = [
                'user_string' => $session->verify_string,
                'business_string' => $business_string
            ];
        
        $save_business = perform_post_curl($purl, $pdata);
        if($save_business){
            if($save_business->status == "success"){
                $message = "";
            } else {
                $message = $save_business->message;
            }
        } else {
            $message = 'Save Business Link Broken';
            echo $message;
        }
        
        $session->message($message);
        redirect_to("business?".$exploding);
    } else {
        die("Wrong Path");
    }
?>