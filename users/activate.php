<?php
    require_once("../../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $coded_string = isset($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($coded_string)){
        $string = base64_decode($coded_string);
        $gurl = "fetch_user_by_string_api.php?string=".$string;
        $users = perform_get_curl($gurl);
        if($users){
            if($users->status == "success"){
                $user = $users->data;
                
                $agurl = "activate_user_api.php?string=".$user->verify_string;
                $activate_user = perform_get_curl($agurl);
                if($activate_user){
                    if($activate_user->status == "success"){
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => "user",
                                'agent_string' => $user->verify_string,
                                'object_type' => "Users",
                                'object_string' => $user->verify_string,
                                "activity" => "Activation",
                                "details" => "{$user->name} activated his/her Account"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("You can now Login to your Account");
                        redirect_to("auth");
                    } else {
                        die($activate_user->message);
                    }
                } else {
                    die("User Activation Link Broken");
                }
            } else {
                die($users->message);
            }
        } else {
            die("User Link Broken");
        }
    } else {
        die("Wrong Path");
    }
?>