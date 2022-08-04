<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("auth");   
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    if(isset($_POST['submit'])){
        $password1 = !empty($_POST['password1']) ? (string)$_POST['password1'] : "";   
        $password2 = !empty($_POST['password2']) ? (string)$_POST['password2'] : "";
        $password3 = !empty($_POST['password3']) ? (string)$_POST['password3'] : "";
        
        $purl = "change_user_password_api.php";
        $pdata = [
                'verify_string' => $session->verify_string,
                'password1' => $password1,
                'password2' => $password2,
                'password3' => $password3
            ];
        $change_password = perform_post_curl($purl, $pdata);
        if($change_password){
            if($change_password->status == "success"){
                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                        'agent_type' => "user",
                        'agent_string' => $session->verify_string,
                        'object_type' => "Users",
                        'object_string' => $session->verify_string,
                        "activity" => "Update",
                        "details" => "Password was changed"
                    ];
                perform_post_curl($lpurl, $lpdata);
                $session->message("Password Changed successfully!");
                redirect_to("user_security");
            } else {
                $message = $change_password->message;
            }
        } else {
            $message = "Password Change Link Broken";
        }
    }
    
    include_portal_template("header.php");
?>

    <main id="main" class="main">
        <div class="row">
            <div class="container">
                <!-- ======= Breadcrumbs ======= -->
                <section class="breadcrumbs">
                    <div class="container">
                        <ol>
                            <li><a href="dashboard">Dashboard</a></li>
                            <li>Security</li>
                        </ol>
                        <h2>User Security</h2>
                        <p>You can change your Password here</p>
                        <?php echo output_message($message); ?>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <div class="card">
                    <div class="card-body card-block pt-3">
                        <form role="form" action="user_security" method="POST" class="row g-3 needs-validation">
                            <div class="col-12">
                                <label for="password1" class="form-label">Current Password</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password1" id="password1" class="form-control" placeholder="Current Password" required>
                                    <span class="input-group-text password-icon" id="inputGroupAppend"><i class="bi bi-eye-slash" id="eyeslash1"></i></span>
                                    <div class="invalid-feedback">Please Enter the your Current Password</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="password2" class="form-label">New Password</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password2" id="password2" class="form-control" placeholder="New Password" required>
                                    <span class="input-group-text password-icon" id="inputGroupAppend"><i class="bi bi-eye-slash" id="eyeslash2"></i></span>
                                    <div class="invalid-feedback">Please Enter the your New Password</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="password3" class="form-label">Confirm New Password</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password3" id="password3" class="form-control" placeholder="Confirm New Password" required>
                                    <span class="input-group-text password-icon" id="inputGroupAppend"><i class="bi bi-eye-slash" id="eyeslash3"></i></span>
                                    <div class="invalid-feedback">Please Enter the your Current Password</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="submit" name="submit" class="btn btn-primary btn-block" value="Submit">
                            </div>  
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>