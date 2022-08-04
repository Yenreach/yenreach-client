<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_billboard_application_by_string_api.php?string=".$string;
    $applications = perform_get_curl($gurl);
    if($applications){
        if($applications->status == "success"){
            $application = $applications->data;
        } else {
            die($applications->message);
        }
    } else {
        die("Pending Application Link Broken");
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
                        <li class="breadcrumb-item"><a href="billboard_applications_pending">Pending Billboard Application</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Application <?php echo $application->code; ?></a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Billboard Application</h4>
                            <?php echo output_message($message); ?>
                        </div>
                        <div class="card-body card-block">
                            <h4 class="text-center"><?php echo $application->title ?></h4>
                            <p>
                                <img src="https://yenreach.com/images/<?php echo $application->filename.".jpg"; ?>" style="width: 400px; max-width: 80%; height: auto; margin: 0 auto;" />
                            </p>
                            <p>
                                <?php echo nl2br($application->text); ?>
                            </p>
                            <p>
                                <table class="table table-striped table-bordered table-responsive-sm text-dark">
                                    <tr>
                                        <th scope="row">Call To Action</th>
                                        <td><?php echo $application->call_to_action_type; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Call To Action Link</th>
                                        <td><?php echo $application->call_to_action_link; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Proposed Start Date</th>
                                        <td><?php echo $application->proposed_start_date; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Status</th>
                                        <td><?php echo application_status($application->stage); ?></td>
                                    </tr>
                                    <?php
                                        if($application->stage > 2){
                                    ?>
                                            <tr>
                                                <th scope="row">Start Date</th>
                                                <td><?php echo $application->start_date; ?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">End Date</th>
                                                <td><?php echo $application->end_date; ?></td>
                                            </tr>
                                    <?php
                                        }
                                    ?>
                                    <tr>
                                        <th scope="row">Remarks</th>
                                        <td><?php echo nl2br($application->remarks); ?></td>
                                    </tr>
                                    <?php
                                        if($application->stage == 3){
                                    ?>
                                            <tr>
                                                <td colspan="2" class="text-center">
                                                    <a href="mark_application_as_paid?<?php echo $application->verify_string ?>" class="btn btn-primary"
                                                    onclick="return confirm('Do you really want to mark this Application as Paid?')">Mark as Paid</a>
                                                </td>
                                            </tr>
                                    <?php
                                        } elseif($application->stage == 4){
                                    ?>
                                            <tr>
                                                <td colspan="2" class="text-center">
                                                    <a href="billboard_application_reject?<?php echo $application->verify_string ?>" class="btn btn-danger"
                                                    onclick="return confirm('Do you really want to cancel this Billboard?')">Cancel</a>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    ?>
                                </table>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>