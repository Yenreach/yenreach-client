<?php
    require_once("../../includes_public/initialize.php");
    
    $method = !empty($_GET['method']) ? (string)$_GET['method'] : "";
    if($method == "online_payment"){
        $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
        $tgurl = "fetch_transaction_by_verify_string_api.php?string=".$string;
        $transactions = perform_get_curl($tgurl);
        if($transactions){
            if($transactions->status == "success"){
                $transaction = $transactions->data;
                
                if($transaction->status == 3){
                    $purl = "mark_billboard_application_as_paid_api.php";
                    $pdata = [
                            "verify_string" => $transaction->subject
                        ];
                    $marked = perform_post_curl($purl, $pdata);
                    if($marked){
                        if($marked->status == "success"){
                            $mark = $marked->data;
                            
                            $ugurl = "fetch_user_by_string_api.php?string=".$mark->user_string;
                            $users = perform_get_curl($users);
                            if($users){
                                if($users->status == "success"){
                                    $user = $users->data;
                                    
                                    class Login {
                                        public $username;
                                        public $id;
                                        public $autho_level;
                                        public $verify_string;
                                        public $user_type;
                                    }
                                    
                                    $login = new Login();
                                    $login->username = $user->email;
                                    $login->id = $user->id;
                                    $login->autho_level = $user->autho_level;
                                    $login->user_type = "user";
                                    $login->verify_string = $user->verify_string;
                                    $session->login($login);
                                    
                                    $lpurl = "add_activity_log_api.php";
                                    $lpdata = [
                                            'agent_type' => "user",
                                            'agent_string' => $session->verify_string,
                                            'object_type' => "BillboardApplications",
                                            'object_string' => $mark->verify_string,
                                            "activity" => "Billboard Application",
                                            "details" => "Billboard Application was paid for"
                                        ];
                                    perform_post_curl($lpurl, $lpdata);
                                    $session->message("Your Billboard Application has been activated");
                                    redirect_to("yenreach_billboard#my_applications");
                                } else {
                                    die($users->message);
                                }
                            } else {
                                die("Users Link Broken");
                            }
                        } else {
                            die($marked->message);
                        }
                    } else {
                        die("Billboard Application Activation Link Broken");
                    }
                } else {
                    die("Payment has bot been verified by Payment Platform");
                }
            } else {
                die($transactions->error);
            }
        } else {
            die("Transaction Link Broken");
        }
    } else {
        die("Payment Method was not provided");
    }
?>