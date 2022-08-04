<?php
    require_once("../../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $params = isset($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($params)){
        $neededdata = explode("/", $params);
        
        $verify_string = isset($neededdata[0]) ? (string)$neededdata[0] : "";
        $gurl = "fetch_admin_by_string_api.php?string=".$verify_string;
        $admins = perform_get_curl($gurl);
        if($admins){
            if($admins->status == "success"){
                $admin = $admins->data;
            
                $p_string = isset($neededdata[1]) ? (string)$neededdata[1] : "";
                $cgurl = "check_admin_password_api.php?user_string=".$admin->verify_string."&p_string=".$p_string;
                $check_password = perform_get_curl($cgurl);
                if($check_password){
                    if($check_password->status == "success"){
                        $placebo = isset($neededdata[2]) ? (string)$neededdata['2'] : "";
                        
                        if(isset($_POST['submit'])){
                            $password1 = !empty($_POST['password1']) ? (string)$_POST['password1'] : "";
                            $password2 = !empty($_POST['password2']) ? (string)$_POST['password2'] : "";
                            
                            if(!empty($password1)){
                                if($password1 === $password2){
                                     $purl = "activate_admin_account_api.php";
                                     $pdata = [
                                            'verify_string' => $admin->verify_string,
                                            'password' => $password1
                                         ];
                                         
                                    $activate = perform_post_curl($purl, $pdata);
                                    if($activate){
                                        if($activate->status == "success"){
                                            $session->message("You can now Login with your Username and Password");
                                            redirect_to("login");
                                        } else {
                                            $message = $activate->message;
                                        }
                                    } else {
                                        $message = "Account Activation Link Broken";
                                    }
                                } else {
                                    $message = "Password was not correctly confirmed";
                                }
                            } else {
                                $message = "Password must be provided";
                            }
                        }
                    } else {
                        die($check_password->message);
                    }
                } else {
                    die("Password Check Link Broken");
                }
            } else {
                die($admins->message);
            }
        } else {
            die("Admin Link Broken");
        }
    } else {
        die("Page Parametres was not provided");
    }
    
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Yenreach ADMIN||LOGIN</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link href="./css/style.css" rel="stylesheet">

</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">Set Password</h4>
                                    <div class="text-dark"><?php echo output_message($message); ?></div>
                                    <form action="activate?<?php echo $verify_string ?>/<?php echo $p_string ?>/<?php echo $placebo ?>" method="POST">
                                        <p>
                                            <b>Username: </b><?php echo $admin->username; ?>
                                        </p>
                                        <div class="form-group">
                                            <input type="password" name="password1" class="form-control" placeholder="New Password">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password2" class="form-control" placeholder="Confirm Password">
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

</body>

</html>