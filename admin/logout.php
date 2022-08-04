<?php
    require_once("../../includes_public/initialize.php");
    if($session->is_logged_in()){
        $session->logout();
    }
    
    redirect_to("dashboard");
?>