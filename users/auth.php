<?php
    require_once("../../includes_public/initialize.php");
    if($session->is_logged_in()){
        redirect_to("dashboard");  
    }
    $page=!empty($_GET['page']) ? (string)$_GET['page'] : "";
    if(isset($_POST['submit'])){
        $username = !empty($_POST['username']) ? (string)$_POST['username'] : "";
        $password = !empty($_POST['password']) ? (string)$_POST['password'] : "";
        
        $purl = "user_login_api.php";
        $pdata = [
                'username' => $username,
                'password' => $password
            ];
        
        $user_login = perform_post_curl($purl, $pdata);
        if($user_login){
            if($user_login->status == "success"){
                $user = $user_login->data;
                if(isset($_COOKIE['yenreach'])){
                    $purl = "transfer_page_visits_api.php";
                    $pdata = [
                            'cookie' => $_COOKIE['yenreach'],
                            'user_string' => $user->verify_string
                        ];
                        
                    perform_post_curl($purl, $pdata);
                }
                $session->login($user);
                
                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                        'agent_type' => "user",
                        'agent_string' => $user->verify_string,
                        'object_type' => "Users",
                        'object_string' => $user->verify_string,
                        "activity" => "Login",
                        "details" => "{$user->name} logged in into his/her Account"
                    ];
                perform_post_curl($lpurl, $lpdata);
                
                if(!empty($page)){
                    redirect_to("https://yenreach.com".$page);
                } else {
                    redirect_to("dashboard");    
                }
            } else {
                $message = $user_login->message;
            }
        } else {
            $message = "User Login Page Broken";
        }
    }
    
    if(isset($_POST['send'])){
        $forgot_email = !empty($_POST['forgot_email']) ? (string)$_POST['forgot_email'] : "";
        
        $gurl = "forgot_email_api.php?string=".$forgot_email;
        $forgot_password = perform_get_curl($gurl);
        if($forgot_password){
            if($forgot_password->status == "success"){
                $passed = $forgot_password->data;
                
                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                        'agent_type' => "user",
                        'agent_string' => $passed->verify_string,
                        'object_type' => "Users",
                        'object_string' => $passed->verify_string,
                        "activity" => "Password Reset",
                        "details" => "{$passed->name} reset his/her Password"
                    ];
                perform_post_curl($lpurl, $lpdata);
                
                redirect_to("forgot_password?".base64_encode($passed->verify_string));
            } else {
                $message = $forgot_password->message;
            }
        } else {
            $message = "Forgot Password Link Broken";
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
      <link
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css"
  rel="stylesheet"
/>
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"
/>
 
      <!-- Google Fonts -->

    
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
                                            <a href="https://yenreach.com" class="logo d-flex align-items-center w-auto">
                                                <img src="../assets/img/logo.png" alt="" height="200px" width="auto">
                                            </a>
                                            
                                        </div><!-- End Logo -->
                                        <div class="pb-2">
                                            <h5 class="card-title text-center pb-0 fs-4">Login to your Account</h5>
                                            <?php echo output_message($message); ?>
                                        </div>
                                        <form role="form" action="auth?page=<?php echo $page; ?>" method="POST" class="row g-3 needs-validation">
                                            <div class="col-12">
                                                <label for="login_username" class="form-label">Username</label>
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person-circle"></i></span>
                                                    <input type="text" name="username" class="form-control" id="login_username" placeholder="Email" required>
                                                    <div class="invalid-feedback">Please enter your Email Address.</div>
                                                </div>
                                            </div>
                        
                                            <div class="col-12">
                                                <label for="yourPassword" class="form-label">Password</label>
                                                <div class="input-group has-validation mb-2">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>
                                                        <input type="password" name="password" class="form-control" id="login_password" placeholder="Password" required>
                                                        <span class="input-group-text" id="inputGroupAppend"><i class="bi bi-eye-slash" onclick="myFunction(this);"></i></span>
                                                    <div class="invalid-feedback">Please enter your password!</div>
                                                </div>
                                            </div>
                        
                                            <div class="col-12">
                                                <button class="btn w-100 text-white" style="background: #00C853;" id="login_submit" name="submit" type="submit">Login</button>
                                            </div>
                                            <div class="col-12 ">
                                                <p class="small mb-0 text-center"><a href="signup">Create an account</a> || <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalToggle">Forgot Password</a></p>
                                            </div>
                                        </form>
                                        
                                        <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalToggleLabel">Forgot Password</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div id="modal-body" class="modal-body">
                                                        <div class="mx-5">
                                                            <form role="form" action="auth" method="POST" class="row g-3 needs-validation">
                                                                <div class="col-12">
                                                                    <div class="input-group has-validation">
                                                                        <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person-circle"></i></span>
                                                                        <input type="text" name="forgot_email" class="form-control" id="forgot_email" placeholder="Registered Email" required>
                                                                        <div class="invalid-feedback">Invalid Email Address.</div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <button class="btn w-100 text-white" style="background: #00C853;" id="send" name="send" type="submit">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                  <div class="modal-footer">
                            
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>    
                    </div>
                </section>
            </div>
        </main>
    
        <!-- Vendor JS Files -->
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/index.js"></script>
        <!-- Template Main JS File -->
        <!--<script src="assets/js/main.js"></script>-->
        <!--<script src="assets/js/extra_script.js"></script>-->
        <script type="text/javascript">
            function myFunction(e){
              var x = document.getElementById("login_password");
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
