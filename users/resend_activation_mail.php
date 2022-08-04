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
                
                $subject = "Account Activation";
                $content = '<i>'.$user->name.'</i>,';
                $content .= '<p>';
                $content .=     'Your Account creation on <a href="https://www.yenreach.com">The Yenreach Platform</a> was succcessful.';
                $content .=     'In order to activate your yenreach Account to have access to all that Yenreach has to offer, please click';
                $content .=     '<br /><br/>';
                $content .=     '<center><a href="https://yenreach.com/users/activate?'.base64_encode($user->verify_string).'">';
                $content .=     '<button style="padding: 8px 15px; border-radius: 5px; background-color: green; color: #FFF; font-size: 17px;">This Link</button></a></center>';
                $content .=     '<br></br>';
                $content .= '</p>';
                    
                $purl = "send_mail_api.php";
                $pdata = [
                        'ticket_id' => '',
                        'movement' => 'outgoing',
                        'from_name' => 'Yenreach',
                        'from_mail' => 'info@yenreach.com',
                        'recipient_name' => $user->name,
                        'recipient_mail' => $user->email,
                        'subject' => $subject,
                        'content' => $content,
                        'reply_name' => 'Yenreach',
                        'reply_mail' => 'info@yenreach.com'
                    ];
                perform_post_curl($purl, $pdata);
                $session->message("Mail has been Resent");
                redirect_to("signup_success?".base64_encode($user->verify_string));
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