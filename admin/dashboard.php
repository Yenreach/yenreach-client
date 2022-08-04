<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("login");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $agurl = "fetch_admin_by_string_api.php?string=".$session->verify_string;
    $admins = perform_get_curl($agurl);
    if($admins){
        if($admins->status == "success"){
            $admin = $admins->data;
        } else {
            die($admins->message);
        }
    } else {
        die("Admin Link Broken");
    }
    
    $ugurl = "fetch_all_users_api.php";
    $users = perform_get_curl($ugurl);
    if($users){
        if($users->status == "success"){
            $user_count = count($users->data);
        } else {
            $user_count = 0;
        }
    } else {
        die("Users Link Broken");
    }
    
    $bgurl = "fetch_all_businesses_api.php";
    $businesses = perform_get_curl($bgurl);
    if($businesses){
        if($businesses->status == "success"){
            $bus_count = count($businesses->data);
        } else {
            $bus_count = 0;
        }
    } else {
        die("Businesess Link Broken");
    }
    
    include_admin_template("header.php");
?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Hi, <?php echo $admin->name; ?></h4>
                            <p class="mb-0">Your Yenreach Admin Dashboard</p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active"><a href="javascript:void(0)"><i class="fa fa-home"></i></a></li>
                        </ol>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-primary">Users</h3>
                            </div>
                            <div class="stat-widget-one card-body">
                                <div class="stat-icon d-inline-block">
                                    <i class="fa fa-users text-primary border-primary"></i>
                                </div>
                                <div class="stat-content d-inline-block">
                                    <div class="stat-digit text-primary">Total | <?php echo $user_count; ?></div>
                                    <div class="stat-text"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-primary">Businesses</h3>
                            </div>
                            <div class="stat-widget-one card-body">
                                <div class="stat-icon d-inline-block">
                                    <i class="fa fa-building text-primary border-primary"></i>
                                </div>
                                <div class="stat-content d-inline-block">
                                    <div class="stat-digit text-primary">Total | <?php echo $bus_count; ?></div>
                                    <div class="stat-text"><a href="all_businesses">All Businesses</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php include_admin_template("footer.php"); ?>