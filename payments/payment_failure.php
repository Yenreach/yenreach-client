<?php
    require_once("../../includes_public/initialize.php");
    
    $msg = "OOPS! There was an error in this Transaction. ";
    $msg .= "<br />";
    if(!empty($message)){
        $msg .= $message."<br />";
    }
    $msg .= "For any complaints or further enquiries, pleae reach out to us by sending a mail to <a href=\"mailto:support@yenreach.com\">support@yenreach.com</a>";
    echo $msg;
?>