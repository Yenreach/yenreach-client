<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $gurl = "fetch_all_users_api.php";
    $users = perform_get_curl($gurl);
    if($users){
        
    } else {
        die("Users Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>All Users</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Users</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">All Users</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">All Users</h4>
                        </div>
                        <div class="card-body card-block">
                            <div class="table-responsive text-dark">
                                <?php
                                    if($users->status == "success"){
                                ?>
                                        <table class="table table-striped table-bordered text-dark" id="businesses_list">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Referral Lead</th>
                                                    <th>Business(es)</th>
                                                    <th>Date Registered</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($users->data as $user){
                                                ?>
                                                        <tr>
                                                            <td><?php echo $user->name; ?></td>
                                                            <td><?php echo $user->email." State"; ?></td>
                                                            <td><?php echo $user->refer_method; ?></td>
                                                            <td><?php
                                                                foreach($user->businesses as $business){
                                                                    echo '<a href="business?'.$business->verify_string.'" class="text-primary">'.$business->name.'</a> ';
                                                                }
                                                            ?></td>
                                                            <td><?php echo strftime("%A, %d %B %Y %I:%M:%S%p", $user->created); ?></td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                <?php
                                    } else {
                                        echo $businesses->message;
                                    }
                                ?> 
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>