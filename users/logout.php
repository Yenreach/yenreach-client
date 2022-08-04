<?php
    require_once("../../includes_public/initialize.php");
    
    $session->logout();
    redirect_to("../");
?>
