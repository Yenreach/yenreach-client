<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    if(isset($_POST['submit'])){
        $password1 = !empty($_POST['password1']) ? (string)$_POST['password1'] : "";   
        $password2 = !empty($_POST['password2']) ? (string)$_POST['password2'] : "";
        $password3 = !empty($_POST['password3']) ? (string)$_POST['password3'] : "";
        
        $purl = "change_admin_password_api.php";
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
                        'agent_type' => "admin",
                        'agent_string' => $session->verify_string,
                        'object_type' => "Admins",
                        'object_string' => $session->verify_string,
                        "activity" => "Update",
                        "details" => "Password was changed"
                    ];
                perform_post_curl($lpurl, $lpdata);
                $session->message("Password Changed successfully!");
                redirect_to("security");
            } else {
                $message = $change_password->message;
            }
        } else {
            $message = "Password Change Link Broken";
        }
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Account Security</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Security</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Change Password</h4>
                            <?php echo output_message($message); ?>
                        </div>
                        <div class="card-body card-block">
                            <form action="security" method="POST">
                                <div class="form-group">
                                    <input type="password" name="password1" id="ch_password1" class="form-control" placeholder="Old Password">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password2" id="ch_password2" class="form-control" placeholder="New Password">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password3" id="ch_password3" class="form-control" placeholder="Confirm new Password">
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

<?php include_admin_template("footer.php"); ?>