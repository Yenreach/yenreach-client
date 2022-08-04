<?php
    require_once("../../includes_public/initialize.php");
    $redirect = !empty($_GET['page']) ? (string)$_GET['page'] : "";
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
                
                if(empty($redirect)){
                    redirect_to("dashboard");
                } else {
                    redirect_to("https://yenreach.com".$redirect);
                }
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
    
      <!-- Google Fonts -->
      <!--<link href="https://fonts.gstatic.com" rel="preconnect">-->
      <!--<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">-->
    
      <!-- Vendor CSS Files -->
      <!--<link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
      <!--<link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">-->
      <!--<link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">-->
      <!--<link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">-->
      <!--<link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">-->
      <!--<link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">-->
      <!--<link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">-->
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
    </head>
    <body>
        <main>
            <div class="container">
                <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-2 ">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-6 h-100 d-flex flex-column align-items-center justify-content-center">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-center">
                                            <a href="https://yenreach.com" class="logo d-flex align-items-center w-auto">
                                                <img src="../assets/img/logo.png" alt="" height="50px" width="auto">
                                            </a>
                                        </div><!-- End Logo -->
                                        <div class="pb-2">
                                            <h5 class="card-title text-center pb-0 fs-4">Create Your <span class="fs-4 fw-bold">FREE</span> Account</h5>
                                            <?php echo output_message($message); ?>
                                        </div>
                                        <form role="form" action="signup?page=<?php echo $redirect; ?>" method="POST" class="row g-3 needs-validation">
                    
                                            <div class="col-12">
                                                <label for="signup_name" class="form-label">Full Name</label>
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person-circle"></i></span>
                                                    <input type="text" name="name" class="form-control" id="signup_name" placeholder="Name (not your business name)"  data-bs-toggle="tooltip" data-bs-placement="top" title="Enter your Full Name, not business name" value="<?php echo $name; ?>" required>
                                                    <div class="invalid-feedback">Please enter your Name.</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="signup_email" class="form-label">Email Address</label>
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-email-circle"></i></span>
                                                    <input type="email" name="email" class="form-control" id="signup_email" placeholder="Email" value="<?php echo $email; ?>" required>
                                                    <div class="invalid-feedback">Please enter your Email Address.</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="signup_password" class="form-label">Create Password</label>
                                                <div class="input-group has-validation mb-2">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>
                                                    <input type="password" name="password1" class="form-control" id="signup_password" placeholder="Password" required>
                                                    <span class="input-group-text password-icon" id="inputGroupAppend"><i class="bi bi-eye-slash" id="eyeslash1"></i></span>
                                                    <div class="invalid-feedback">Please enter your Password!</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="conf_password" class="form-label">Confirm Password</label>
                                                <div class="input-group has-validation mb-2">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>
                                                    <input type="password" name="password2" class="form-control" id="conf_password" placeholder="Confirm Password" required>
                                                    <span class="input-group-text password-icon" id="inputGroupAppend"><i class="bi bi-eye-slash" id="eyeslash2"></i></span>
                                                    <div class="invalid-feedback">Please confirm your Password!</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="conf_password" class="form-label">How did you know about Yenreach?</label>
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
                                                 <div class='col-12 w-100 '>
                                                <p class="small mb-0 text-center w-100 d-flex align-items-center justify-content-around">
                                                    
                                                Read our
                                            <a href="../terms.html" class="logo d-flex align-items-center w-auto">terms of service</a>
                                            and
                                            <a href="../policy.html" class="logo d-flex align-items-center w-auto">privacy policy</a>
                                                </p>
                                            </div>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn w-100 text-white" style="background: #00C853;" id="signup_submit" name="submit" type="submit">Signup</button>
                                            </div>
                                           
                                            <div class="col-12 ">
                                                <p class="small mb-0 text-center"><a href="auth?page=<?php echo $redirect; ?>">Have an Account? Login</a></p>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>

        <!--<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>-->
        <!--<script src="../assets/vendor/php-email-form/validate.js"></script>-->
        <!--<script src="../assets/vendor/quill/quill.min.js"></script>-->
        <!--<script src="../assets/vendor/tinymce/tinymce.min.js"></script>-->
        <!--<script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>-->
        <!--<script src="../assets/vendor/chart.js/chart.min.js"></script>-->
        <!--<script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>-->
        <!--<script src="../assets/vendor/echarts/echarts.min.js"></script>-->
        <!--<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>-->
        
        <!-- Template Main JS File -->
        <script src="assets/js/main.js"></script>
        <script>
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        </script>
        <!--<script src="assets/js/extra_script.js"></script>-->
    </body>
</html>