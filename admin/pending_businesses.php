<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout.php");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout.php");
    }
    
    $gurl = "fetch_pending_businesses_api.php";
    $businesses = perform_get_curl($gurl);
    if($businesses){
        
    } else {
        die("Businesses Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Pending Businesses</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Businesses</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Pending Businesses</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Pending Businesses</h4>
                        </div>
                        <div class="card-body card-block">
                            <div class="table-responsive text-dark">
                                <?php
                                    if($businesses->status == "success"){
                                ?>
                                        <table class="table table-responsive-sm text-dark table-striped text-dark">
                                            <thead>
                                                <tr>
                                                    <th>Date Registered</th>
                                                    <th>Business Name</th>
                                                    <th>Email Address</th>
                                                    <th>Phone Number</th>
                                                    <th>Business Owner</th>
                                                    <th>Owner's Email</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($businesses->data as $business){
                                                ?>
                                                        <tr>
                                                            <td><?php echo strftime("%Y.%m.%d %H.%M.%S", $business->created); ?></td>
                                                            <td><?php echo html_entity_decode($business->name); ?></td>
                                                            <td><?php echo $business->email; ?></td>
                                                            <td><?php echo $business->phonenumber; ?></td>
                                                            <td><?php echo $business->owner_name; ?></td>
                                                            <td><?php echo $business->owner_email; ?></td>
                                                            <td><a href="pending_busness?<?php echo $business->verify_string; ?>" class="text-primary">More Details</a></td>
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