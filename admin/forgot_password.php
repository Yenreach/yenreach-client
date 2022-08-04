<?php
    require_once("../../includes_public/initialize.php");
    
    if(isset($_POST['submit'])){
        $username = !empty($_POST['username']) ? (string)$_POST['username'] : "";
        
        $gurl = "admin_forgot_password_api.php?username=".$username;
        $forgotten = perform_get_curl($gurl);
        if($forgotten){
            if($forgotten->status == "success"){
                $forgot = $forgotten->data;
                redirect_to("password_forgot?email=".$forgot->personal_email);
            } else {
                $message = $forgotten->message;
            }
        } else {
            $message = "Forgot Password Link Broken";
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
                                    <h4 class="text-center mb-4">Forgot Password</h4>
                                    <div class="text-dark"><?php echo output_message($message); ?></div>
                                    <form action="forgot_password.php" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo $username; ?>">
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