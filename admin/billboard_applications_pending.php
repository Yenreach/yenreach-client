<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $gurl = "fetch_pending_billboard_applications_api.php";
    $applications = perform_get_curl($gurl);
    if($applications){
        
    } else {
        die("Billboard Applications Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Pending Billboard Applications</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Billboard Applications</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Pending Billboard Application</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Pending Applications</h4>
                        </div>
                        <div class="card-body card-block">
                            <?php
                                if($applications->status == "success"){
                            ?>
                                    <table class="table table-striped table-bordered table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">Applicant</th>
                                                <th scope="col">Headline</th>
                                                <th scope="col">Proposed Starting Date</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($applications->data as $application){
                                            ?>
                                                    <tr>
                                                        <td><a href="user?<?php echo $application->user->verify_string ?>" class="text-primary"><?php echo $application->user->name; ?></a></td>
                                                        <td><?php echo $application->title; ?></td>
                                                        <td><?php echo $application->proposed_start_date; ?></td>
                                                        <td><a class="btn btn-primary" href="billboard_application_pending?<?php echo $application->verify_string; ?>">More Details</a></td>
                                                    </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                            <?php
                                } else {
                                    echo '<p class="text-dark">'.$applications->message.'</p>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>