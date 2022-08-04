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
                    $sgurl = "fetch_subscriptionpayment_by_string_api.php?string=".$transaction->subject;
                    $sub_payments = perform_get_curl($sgurl);
                    if($sub_payments){
                        $sub_payment = $sub_payments->data;
                        $purl = "business_subscription_api.php";
                        $pdata = [
                                'verify_string' => $sub_payment->verify_string,
                                'payment_method' => $method,
                                'amount_paid' => $transaction->amount
                            ];
                        $subscribe_business = perform_post_curl($purl, $pdata);
                        if($subscribe_business){
                            if($subscribe_business->status == "success"){
                                $subscribe = $subscribe_business->data;
                                $ugurl = "fetch_user_by_string_api.php?string=".$subscribe->user_string;
                                $users = perform_get_curl($ugurl);
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
                                        $session->business_login($subscribe->business_string);
                                        $lpurl = "add_activity_log_api.php";
                                        $lpdata = [
                                                'agent_type' => "user",
                                                'agent_string' => $session->verify_string,
                                                'object_type' => "Businesses",
                                                'object_string' => $subscribe->business_string,
                                                "activity" => "Subscribing to Business",
                                                "details" => "Made Subscription to the Business"
                                            ];
                                        perform_post_curl($lpurl, $lpdata);
                                        $session->message("Your Subscription on this Business was succesful");
                                        redirect_to("business_subscriptions");
                                    } else {
                                        die("3".$users->message);
                                    }
                                } else {
                                    die("User Link Broken");
                                }
                            } else {
                                die("2".$subscribe_business->message);
                            }
                        } else {
                            die("Business Subscription Link Broken");
                        }
                    } else {
                        die("Subscription Payments Link Broken");
                    }
                } else {
                    die("Payment has not been verified");
                }
            } else {
                die("1".$transactions->message);
            }
        } else {
            die("Transaction Link Broken");
        }
    } else {
        die("Subscription Payment Method was not provided");
    }
?>