<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    if(isset($_POST['submit'])){
        $name = !empty($_POST['name']) ? (string)$_POST['name'] : "";
        $username = !empty($_POST['username']) ? (string)$_POST['username'] : "";
        $personal_email = !empty($_POST['personal_email']) ? (string)$_POST['personal_email'] : "";
        $official_email = !empty($_POST['official_email']) ? (string)$_POST['official_email'] : "";
        $phone = !empty($_POST['phone']) ? (string)$_POST['phone'] : "";
        
        $user_level = !empty($session->user_autho_level) ? (int)$session->user_autho_level : 0;
        $autho_level = $user_level + 1;
        
        $purl = "add_admin_api.php";
        $pdata = [
                'name' => $name,
                'personal_email' => $personal_email,
                'official_email' => $official_email,
                'phone' => $phone,
                'username' => $username,
                'autho_level' => $autho_level
            ];
        $add_admin = perform_post_curl($purl, $pdata);
        if($add_admin){
            if($add_admin->status == "success"){
                $add = $add_admin->data;
                
                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                        'agent_type' => $session->user_type,
                        'agent_string' => $session->verify_string,
                        'object_type' => "Admins",
                        'object_string' => $add->verify_string,
                        "activity" => "Creation",
                        "details" => "{$name} added as an Admin"
                    ];
                perform_post_curl($lpurl, $lpdata);
                
                $session->message($add->name." has been added as an Admin");
                redirect_to("admins");
            } else {
                $message = $add_admin->message;
            }
        } else {
            $message = "Admin Addition Link Broken";
        }
    } else {
        $name = "";
        $username = "";
        $personal_email = "";
        $official_email = "";
        $phone = "";
    }
    
    $gurl = "fetch_admins_api.php";
    $admins = perform_get_curl($gurl);
    if($admins){
        
    } else {
        die("Admins Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Website Administrators</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Components</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Admins</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Admins</h4>
                        </div>
                        <div class="card-body card-block">
                            <?php
                                if($admins->status == "success"){
                            ?>
                                    <div class="table-responsive text-dark">
                                        <table class="table table-responsive-sm table-striped text-dark">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Username</th>
                                                    <th scope="col">Personal Email</th>
                                                    <th scope="col">Official Email</th>
                                                    <th scope="col">Phone</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Date Added</th>
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($admins->data as $admin){
                                                ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $admin->name; ?></td>
                                                            <td class="text-center"><?php echo $admin->username; ?></td>
                                                            <td class="text-center"><?php echo $admin->personal_email; ?></td>
                                                            <td class="text-center"><?php echo $admin->official_email; ?></td>
                                                            <td class="text-center"><?php echo $admin->phone; ?></td>
                                                            <td class="text-center"><?php
                                                                if($admin->activation == 1){
                                                                    echo "Deactivated";
                                                                } elseif($admin->activation == 2){
                                                                    echo "Activated";
                                                                } elseif($admin->activation == 0){
                                                                    echo "Deleted";
                                                                }
                                                            ?></td>
                                                            <td class="text-center"><?php echo strftime("%A, %d %B, %Y; %I:%M:%S%p", $admin->created); ?></td>
                                                            <td class="text-center">
                                                                <?php
                                                                    if($session->user_autho_level < $admin->autho_level){
                                                                ?>
                                                                        <a class="text-primary" href="admin_edit?<?php echo $admin->verify_string; ?>">Edit/Update</a>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php
                                                                    if($session->user_autho_level < $admin->autho_level){
                                                                ?>
                                                                        <a class="text-primary" href="admin_activation?<?php echo $admin->verify_string; ?>"><?php
                                                                            if($admin->activation == 1){
                                                                                echo "Activate";
                                                                            } else{
                                                                                echo "Deactivate";
                                                                            }
                                                                        ?></a>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php
                                                                    if($session->user_autho_level < $admin->autho_level){
                                                                ?>
                                                                        <a class="text-primary" href="admin_delete?<?php echo $admin->verify_string; ?>" onclick="return confirm('Do you really want ti delete this Admin?')">Delete</a>      
                                                                <?php
                                                                    }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>    
                                    </div>
                            <?php
                                } else {
                                    echo '<p class="text-dark">'.$admins->message.'</p>';
                                }
                            ?>    
                        </div>
                    </div>
                </div>
            </div>
            <?php
                if($session->user_autho_level <= 2){
            ?>
                    <div class="row">
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Add Admin</h4>
                                </div>
                                <div class="card-body card-block">
                                    <form action="admins" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="name" class="form-control" id="admin_name" placeholder="Name" value="<?php echo $name; ?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control" id="admin_username" placeholder="Username" value="<?php echo $username; ?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" name="personal_email" class="form-control" id="admin_personalemail" placeholder="Personal Email Address" value="<?php echo $personal_email; ?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" name="official_email" class="form-control" id="admin_officialemail" placeholder="Official Email Address" value="<?php echo $official_email; ?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" name="phone" class="form-control" id="admin_phone" placeholder="Phone" value="<?php echo $phone; ?>">
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            ?>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>