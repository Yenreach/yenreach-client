<?php
    require_once("../../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    
    if(!$session->is_logged_in()){
        redirect_to("auth?page=".$url);   
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    $session->business_string = $_SESSION['business_string'] = "";
    if(isset($message)){
        $session->message($message);
    }
    redirect_to("add_business");
?>