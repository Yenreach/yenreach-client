<?php
    require_once("../../includes_public/initialize.php");
    if($session->is_logged_in()){
        redirect_to("dashboard");
    }
    if(isset($_POST['submit'])){
        $name = !empty($_POST['name']) ? (string)$_POST['name'] : "";
        $email = !empty($_POST['email']) ? (string)$_POST['email'] : "";
        $password1 = !empty($_POST['password1']) ? (string)$_POST['password1'] : "";
        $password2 = !empty($_POST['password2']) ? (string)$_POST['password2'] : "";
        $refer_method = !empty($_POST['refer_method']) ? (string)$_POST['refer_method'] : "";
        
        $purl = "add_user_api.php";
        $pdata = [
                'name' => $name,
                'email' =>$email,
                'password1' => $password1,
                'password2' => $password2,
                'refer_method' => $refer_method
            ];
        
        $add_user = perform_post_curl($purl, $pdata);
        if($add_user){
            if($add_user->status == "success"){
                $user = $add_user->data;
                
                $redirect_to("signup_success?".$user->verify_string);
            } else {
                $message = $add_user->message;
            }
        } else {
            $message = "User Signup Link Broken";
        }
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
    
    <main>
        <div class="container">
            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                            <div class="d-flex justify-content-center py-4">
                                <a href="../index.php" class="logo d-flex align-items-center w-auto">
                                    <img src="../assets/img/logo.png" alt="">
                                    <span class="d-none d-lg-block text-white">yenreach.com</span>
                                </a>
                            </div><!-- End Logo -->
            
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Create your Account</h5>
                                    </div>
                                    <form role="form" action="signup" method="POST" class="row g-3 needs-validation">

                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Username</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person-circle"></i></span>
                                                <input type="text" name="username" class="form-control" id="username" placeholder="Email" required>
                                                <div class="invalid-feedback">Please enter your username.</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <div class="input-group has-validation mb-2">
                                                <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>
                                                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                                                <span class="input-group-text" id="inputGroupAppend"><i class="bi bi-eye-slash" onclick="myFunction(this);"></i></span>
                                                <div class="invalid-feedback">Please enter your password!</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button class="btn w-100 text-white" style="background: #00C853;" id="save" name="save" type="submit">Login</button>
                                        </div>
                                        <div class="col-12 ">
                                            <p class="small mb-0 text-center"><a href="../add-business.php">Create an account</a> || <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalToggle">Reset Password</a></p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </section>
        </div

<body>