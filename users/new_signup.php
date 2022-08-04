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
                
                class Login {
                    public $id;
                    public $verify_string;
                    public $username;
                    public $user_type;
                    public $autho_level;
                }
                
                $login = new Login();
                $login->id = $user->id;
                $login->verify_string = $user->verify_string;
                $login->username = $user->email;
                $login->user_type = "user";
                $login->autho_level = $user->autho_level;
                $session->login($login);
                
                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                        'agent_type' => "user",
                        'agent_string' => $user->verify_string,
                        'object_type' => "Users",
                        'object_string' => $user->verify_string,
                        "activity" => "Creation",
                        "details" => "{$name} Signed up as a User on the Website"
                    ];
                perform_post_curl($lpurl, $lpdata);
                
                redirect_to("dashboard");
            } else {
                $message = $add_user->message;
            }
        } else {
            $message = "User Signup Link Broken";
        }
    } else {
        $name = "";
        $email = "";
        $refer_method = "";
    }
    
?>

<!DOCTYPE html>
<html lang="en">

    <head>
      <meta charset="utf-8">
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
      <title>Yenreach || Signup</title>
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

<!--<link rel="stylesheet" href="/assets/css/index.css" />-->
    
      <!-- Template Main CSS File -->
      <link href="assets/css/style.css" rel="stylesheet">
    
      <!-- =======================================================
      * Template Name: NiceAdmin - v2.1.0
      * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
      * Author: BootstrapMade.com
      * License: https://bootstrapmade.com/license/
      ======================================================== -->
      
      <style>
    /*     .side-nav{*/
    /*height: 100vh;*/
    /*background:#00c853;*/
  /*  background: linear-gradient(*/
  /*    rgba(0, 0, 0, 0.6),*/
  /*    rgba(0, 0, 0, 0.6),*/
  /*    rgba(0, 0, 0, 0.6)*/
  /*  ); */
  /*  background-repeat: no-repeat;*/
  /*    background-position: center;*/
  /*background-size: cover';*/
         } 
      </style>
    </head>
    <body>
         <div class="row">
      <div
        class="middle-container col-10 mx-auto mt-4 rounded lg-shadow-lg px-2"
      >
      <div class=" d-md-none py-2">
    </div>
        <div class="col-6 col-md d-flex flex-column justify-content-center d-none registration-background d-lg-block">
          <div class="login-image-container h-100 mx-auto rounded ">
              <div class="sign-up-text-container">
                  <h1 class="text-center text-dark fs-4">CREATE AN ACCOUNT</h1>
                  <div>
                <a href="../index.php" class="logo d-flex align-items-center justify-content-center">
                <img src="../assets/img/yenreach_logo.png" alt="logo" width='100%' >
             </a>
                  </div>
                <!--<div class="bg-danger">-->
                <!--    <a href="../index.php" class="logo d-flex align-items-center w-auto bg-danger " style='height:100rem;width:300rem'>-->
                <!--                    </a>-->
                <!--                </div>-->

                  <!--<h1 class="text-center fs-1">YENREACH.COM</h1>-->
                  <p class="text-center">The Fastest Growing Business Directory Platform in Nigeria</p>
              </div>
              <div class="w-75 d-flex align-items-center justify-content-center">
                  <button class="btn px-5 py-3 bg-white text-success shadow">Log in</button>
                  <a href="auth" class="btn px-5 py-3 shadow">Sign Up</a>
                  <!-- <div class="col-12 ">
                    <p class="small mb-0 text-center"><a >Have an Account? Login</a></p>
                </div> -->
              </div>
            <!-- <img
              src="https://res.cloudinary.com/dxfq3iotg/image/upload/v1557204172/banner_2.jpg"
              alt=""
              width="100%"
              height="100%"
            /> -->
          </div>
        </div>
        <div class="col-lg-5 col-12 mx-auto form-container rounded ">
        
                                        <form role="form" action="signup" method="POST" class=" col-11 h-100 mx-auto needs-validation d-flex flex-column justify-content-around align-items-center py-3">
                                          <div class="pb-2 d-lg-none">
                                            <h5 class="card-title text-center pb-0 fs-4">Create Your FREE Account</h5>
                                        </div>
                                            <div class="col-12">
                                                <label for="signup_name" class="form-label">Name</label>
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person-circle"></i></span>
                                                    <input type="text" name="name" class="form-control" id="signup_name" placeholder="First and Last Name" value="<?php echo $name; ?>" required>
                                                    <div class="invalid-feedback">Please enter your Name.</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="signup_email" class="form-label">Email</label>
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-envelope"></i></span>
                                                    <input type="email" name="email" class="form-control" id="signup_email" placeholder="Your email address" value="<?php echo $email; ?>" required>
                                                    <div class="invalid-feedback">Please enter your Email Address.</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="signup_password" class="form-label">Password</label>
                                                <div class="input-group has-validation mb-2">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>
                                                    <input type="password" name="password1" class="form-control password" id="signup_password" placeholder="Create new password" required>
                                                    <span class="input-group-text password-icon" id="inputGroupAppend"><i class="bi bi-eye" id="eyeslash1"></i></span>
                                                    <div class="invalid-feedback">Please enter your Password!</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="conf_password" class="form-label">Confirm Password</label>
                                                <div class="input-group has-validation mb-2">
                                                    <span class="input-group-text" id="inputGroupPrepend">
                                                        <i class="bi bi-lock"></i>
                                                        </span>
                                                    <input type="password" name="password2" class="form-control password" id="conf_password" placeholder="Confirm Password" required>
                                                    <span class="input-group-text password-icon" id="inputGroupAppend"><i class="bi bi-eye" id="eyeslash2"></i></span>
                                                    <div class="invalid-feedback">Please confirm your Password!</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="conf_password" class="form-label">How did you hear about Yenreach?</label>
                                                <div class="input-group has-validation mb-2">
                                                    <!--<span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>-->
                                                    <select name="refer_method" id="refer_method" class="form-control">
                                                        <option value="Facebook" <?php if($refer_method == "Facebook"){ echo "selected"; } ?>>Facebook</option>
                                                        <option value="Instagran" <?php if($refer_method == "Instagran"){ echo "selected"; } ?>>Instagram</option>
                                                        <option value="Billboards" <?php if($refer_method == "Billboards"){ echo "selected"; } ?>>Billboards</option>
                                                        <option value="Radio/Tv Adverts" <?php if($refer_method == "Radio/Tv Adverts"){ echo "selected"; } ?>>Radio/TV Adverts</option>
                                                        <option value="Personal Referral" <?php if($refer_method == "Personal Referral"){ echo "selected"; } ?>>Personal Referral</option>
                                                        <option value="Others" <?php if($refer_method == "Others"){ echo "selected"; } ?>>Others</option>
                                                    </select>
                                                </div>
                                                 <div class='w-100'>
                                                 <p class="small mb-0 col-12 py-1 col-sm-10 col-lg-10 text-center  align-items-center justify-content-evenly d-none d-md-flex">
                                                Read our
                                            <a href="../terms.html" class="logo d-flex align-items-center w-auto text-muted">terms of service</a>
                                            and
                                            <a href="../policy.html" class="logo d-flex align-items-center w-auto text-muted">privacy policy</a>
                                                </p>
                                                      <p class="mx-auto small mb-0 col-6 py-1 col-sm-8 col-lg-10 text-center d-flex align-items-center justify-content-evenly  d-md-none ">
                                                    
                                                Read our
                                            <a href="../terms.html" class="logo d-flex align-items-center w-auto text-muted">terms</a>
                                            and
                                            <a href="../policy.html" class="logo d-flex align-items-center w-auto text-muted"> policy</a>
                                                </p>
                                            </div>
                                          
                                         
                                            </div>
                                            <div class="col-12">
                                                <button class="btn w-100 text-white shadow py-2" style="background: #00C853;" id="signup_submit" name="submit" type="submit">Signup</button>
                                                <div class="col-12 d-lg-none ">
                                                  <p class="small mb-0 mt-1 text-center">
                                                    Have an Account?
                                                    <a href="auth"> Login</a></p>
                                              </div>
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
    
        <!-- Vendor JS Files -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Template Main JS File -->
        <script src="assets/js/main.js"></script>
        <script src="assets/js/extra_script.js"></script>
    </body>
</html>