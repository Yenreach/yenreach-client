<?php
    require_once("../../includes_public/initialize.php");
    if($session->is_logged_in()){
        redirect_to("dashboard");
    }
    
    if(isset($_POST['submit'])){
        $username = !empty($_POST['username']) ? (string)$_POST['username'] : "";
        $password = !empty($_POST['password']) ? (string)$_POST['password'] : "";
        
        $purl = "admin_login_api.php";
        $pdata = [
                'username' => $username,
                'password' => $password
            ];
        
        $admin_login = perform_post_curl($purl, $pdata);
        if($admin_login){
            if($admin_login->status == "success"){
                $admin = $admin_login->data;
                
                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                        'agent_type' => "admin",
                        'agent_string' => $admin->verify_string,
                        'object_type' => "Admins",
                        'object_string' => $admin->verify_string,
                        "activity" => "Login",
                        "details" => "Logged in into the Account"
                    ];
                    
                perform_post_curl($lpurl, $lpdata);
                $session->login($admin);
                redirect_to("dashboard");
            } else {
                $message = $admin_login->message;
            }
        } else {
            $message = "Admin Login Page Link Broken!";
        }
    } else {
        $username = "";
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
                                    <h4 class="text-center mb-4">Sign In</h4>
                                    <div class="text-dark"><?php echo output_message($message); ?></div>
                                    <form action="login" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo $username; ?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control" placeholder="Password">
                                        </div>
                                        <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                            <div class="form-group">
                                                <a href="forgot_password">Forgot Password?</a>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" name="submit" class="btn btn-primary btn-block">Signin</button>
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