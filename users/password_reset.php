<?php
    require_once("../../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $params = isset($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($params)){
        $neededdata = explode("/", $params);
        
        $verify_string = isset($neededdata[0]) ? (string)$neededdata[0] : "";
        $gurl = "fetch_user_by_string_api.php?string=".$verify_string;
        $users = perform_get_curl($gurl);
        if($users){
            if($users->status == "success"){
                $user = $users->data;
            
                $p_string = isset($neededdata[1]) ? (string)$neededdata[1] : "";
                $cgurl = "check_user_password_api.php?user_string=".$user->verify_string."&p_string=".$p_string;
                $check_password = perform_get_curl($cgurl);
                if($check_password){
                    if($check_password->status == "success"){
                        $placebo = isset($neededdata[2]) ? (string)$neededdata['2'] : "";
                        
                        if(isset($_POST['submit'])){
                            $password1 = !empty($_POST['password1']) ? (string)$_POST['password1'] : "";
                            $password2 = !empty($_POST['password2']) ? (string)$_POST['password2'] : "";
                            
                            if(!empty($password1)){
                                if($password1 === $password2){
                                     $purl = "reset_user_password_api.php";
                                     $pdata = [
                                            'verify_string' => $user->verify_string,
                                            'password' => $password1
                                         ];
                                         
                                    $activate = perform_post_curl($purl, $pdata);
                                    if($activate){
                                        if($activate->status == "success"){
                                            $session->message("You can now Login with your Username and Password");
                                            redirect_to("auth");
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
<html lang="en">

    <head>
      <meta charset="utf-8">
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
      <title>Yenreach || Dashboard</title>
      <meta content="" name="description">
      <meta content="" name="keywords">
    
      <!-- Favicons -->
      <link href="../assets/img/favicon.png" rel="icon">
      <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    
      <!-- Google Fonts -->
      <link href="https://fonts.gstatic.com" rel="preconnect">
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    
      <!-- Vendor CSS Files -->
      <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
      <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
      <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
      <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
      <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
      <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
    
      <!-- Template Main CSS File -->
      <link href="assets/css/style.css" rel="stylesheet">
    
      <!-- =======================================================
      * Template Name: NiceAdmin - v2.1.0
      * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
      * Author: BootstrapMade.com
      * License: https://bootstrapmade.com/license/
      ======================================================== -->
    </head>
    <body>
        <main>
            <div class="container">
                <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-center">
                                            <a href="../index.php" class="logo d-flex align-items-center w-auto">
                                                <img src="../assets/img/logo.png" alt="" height="200px" width="auto">
                                            </a>
                                        </div><!-- End Logo -->
                                        <div class="pb-2">
                                            <h5 class="card-title text-center pb-0 fs-4">Reset your Your Password</h5>
                                            <?php echo output_message($message); ?>
                                        </div>
                                        <form role="form" action="password_reset?<?php echo $verify_string ?>/<?php echo $p_string ?>/<?php echo $placebo ?>" method="POST" class="row g-3 needs-validation">
                                            <div class="col-12">
                                                <label for="login_username" class="form-label">Password</label>
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>
                                                    <input type="password" name="password1" class="form-control" id="password1" placeholder="Password" required>
                                                    <span class="input-group-text" id="inputGroupAppend"><i class="bi bi-eye-slash" onclick="myFunction(this);"></i></span>
                                                    <div class="invalid-feedback">Please enter your Password</div>
                                                </div>
                                            </div>
                        
                                            <div class="col-12">
                                                <label for="yourPassword" class="form-label">Password</label>
                                                <div class="input-group has-validation mb-2">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>
                                                        <input type="password" name="password2" class="form-control" id="password2" placeholder="Confirm Password" required>
                                                        <span class="input-group-text" id="inputGroupAppend"><i class="bi bi-eye-slash" onclick="myFunction(this);"></i></span>
                                                    <div class="invalid-feedback">Please confirm your password!</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <button class="btn w-100 text-white" style="background: #00C853;" id="login_submit" name="submit" type="submit">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>    
                    </div>
                </section>
            </div>
        </main>
    
        <!-- Vendor JS Files -->
        <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
        <script src="../assets/vendor/php-email-form/validate.js"></script>
        <script src="../assets/vendor/quill/quill.min.js"></script>
        <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
        <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
        <script src="../assets/vendor/chart.js/chart.min.js"></script>
        <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
        <script src="../assets/vendor/echarts/echarts.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        
        <!-- Template Main JS File -->
        <script src="assets/js/main.js"></script>
        <script src="assets/js/extra_script.js"></script>
        <script type="text/javascript">
            function myFunction(e){
              var x = document.getElementById("password");
              if(x.type === "password") {
                x.type = "text";
              } else {
                x.type = "password";
              }
              e.classList.toggle('bi-eye');
          }
        </script>
    </body>
</html>