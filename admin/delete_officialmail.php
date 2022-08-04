<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to('logout.php');   
    }
    if($session->user_type != 'admin'){
        redirect_to('logout.php');
    }
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $verify_string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl ="delete_officialmail_api.php?string=".$verify_string;
    $mails = perform_get_curl($gurl);
    if($mails){
        if($mails->status == "success"){
            $mail = $mails->data;
            $lpurl = "add_activity_log_api.php";
            $lpdata = [
                    'agent_type' => $session->user_type,
                    'agent_string' => $session->verify_string,
                    'object_type' => "MailPasswords",
                    'object_string' => $add_mail->data->verify_string,
                    "activity" => "Delete",
                    "details" => $mail->email." deleted from the list of Official Mails"
                ];
                        
            perform_post_curl($lpurl, $lpdata);
                    
            $message = $mail->email." was successfully deleted";
        } else {
            $message = $mails->message;
        }
    } else {
        $message = "Official Mail Delete Link Broken";
    }
    
    $session->message($message);
    redirect_to("officialmails");
?>