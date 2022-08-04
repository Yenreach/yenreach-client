<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");   
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $purl = "check_billboard_eligibility_for_payment.php";
    $pdata = [
            'verify_string' => $string,
            'user_string' => $session->verify_string
        ];
    $checked = perform_post_curl($purl, $pdata);
    if($checked){
        if($checked->status == "success"){
            redirect_to("https://payments.yenreach.com/make_payment?platform=Flutterwave&user_type=user&user_string={$session->verify_string}&reason=billboard_payment&subject={$string}");
        } else {
            $session->message($checked->message);
            redirect_to("billboard_applied?{$string}");
        }
    } else {
        $session->message("Billboard Payment Link Broken");
        redirect_to("billboard_applied?{$string}");
    }
?>