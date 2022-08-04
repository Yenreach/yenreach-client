<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("auth");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    $gurl = "fetch_user_by_string_api.php?string=".$session->verify_string;
    $users = perform_get_curl($gurl);
    if($users){
        if($users->status == "success"){
            $user = $users->data;
            
            if(isset($_POST['submit'])){
                $name = !empty($_POST['name']) ? (string)$_POST['name'] : "";
                $email = $user->email;
                
                $purl = "edit_user_profile_api.php";
                $pdata = [
                        'verify_string' => $user->verify_string,
                        'name' => $name,
                        'email' => $email
                    ];
                $edit_user = perform_post_curl($purl, $pdata);
                if($edit_user){
                    if($edit_user->status == "success"){
                        $edit = $edit_user->data;
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => "user",
                                'agent_string' => $session->verify_string,
                                'object_type' => "Users",
                                'object_string' => $session->verify_string,
                                "activity" => "Update",
                                "details" => "Profile was edited"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("Profile edited successfully");
                        redirect_to("user_profile");
                    } else {
                        $message = $edit_user->message;
                    }
                } else {
                    $message = "User Edit Link Broken";
                }
            } else {
                $name = $user->name;
                $email = $user->email;
            }
        } else {
            die($users->message);
        } 
    } else {
        die("Users Link Broken");
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
                            <li>Profile</li>
                        </ol>
                        <h2>User Profile</h2>
                        <p>Your Personal Profile</p>
                        <?php echo output_message($message); ?>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body card-block  p-3">
                        <div class="col-12">
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Profile Picture</strong></div>
                                <div class="col-lg-9 col-md-8"><?php
                                    if(!empty($user->image) && file_exists("../".$user->image)){
                                ?>
                                        <p>
                                            <img src="<?php echo "../".$user->image; ?>" style="width: 80%; max-width: 300px;" alt="<?php echo $user->name; ?>" />
                                        </p>
                                        <p>
                                            <a href="change_profile_picture" class="btn btn-primary"><strong>Change Photo</strong></a>
                                            &nbsp;
                                            <a href="remove_profile_picture" class="btn btn-danger" onclick="return confirm('Do you really want to remove your Profile Photo?')">Remove Photo</a>
                                        </p>
                                <?php
                                    } else {
                                ?>
                                        <a href="change_profile_picture" class="btn btn-primary">Upload Photo</a>
                                <?php
                                    }   
                                ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Name</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo $user->name; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Email Address</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo $user->email; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-9 col-md-8">
                                    <p><a href="#edit_profile" class="btn btn-primary">Edit Profile</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="edit_profile">
            <div class="col-lg-9 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Profile</h4>
                        <?php echo output_message($message); ?>
                    </div>
                    <div class="card-body card-block">
                        <form role="form" action="user_profile" method="POST" class="row g-3 needs-validation">
                            <div class="col-12">
                                <label for="name" class="form-label">Name</label>
                                <div class="input-group has-validation">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="<?php echo $name; ?>" required>
                                    <div class="invalid-feedback">Please Enter the your Name</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group has-validation">
                                    <input type="email" name="email" id="email" class="form-control" readonly placeholder="Email Address" value="<?php echo $email; ?>" required>
                                    <div class="invalid-feedback">Please provide your Email Address</div>
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