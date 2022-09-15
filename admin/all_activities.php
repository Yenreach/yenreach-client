<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $gurl = "fetch_all_activity_log_api.php";
    $activities = perform_get_curl($gurl);
    if($activities){
        if ($activities->status == "success") {
            $activities = $activities->data;
        } else {
            $activities = array();
            die($activities->message);
        }
    } else {
        die("Activity Link Broken");
    }
    
    function formatDate($timestamp) {
        $created_at = getdate($timestamp);
        echo "$created_at[hours]:$created_at[minutes]:$created_at[seconds], $created_at[weekday], $created_at[month] $created_at[mday], $created_at[year]";
    }
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Activity Log</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Activities</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">All Activity</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">All Activity</h4>
                        </div>
                        <div class="card-body card-block">
                            <div class="table-responsive text-dark">
                                <?php
                                    if(!empty($activities)){
                                ?>
                                        <table class="table table-striped table-bordered text-dark" id="businesses_list">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Activity String</th>
                                                    <th>Agent Type</th>
                                                    <th>Agent String</th>
                                                    <th>Object Type</th>
                                                    <th>Object String</th>
                                                    <th>Activity</th>
                                                    <th>Details</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($activities as $activity){
                                                ?>
                                                        <tr>
                                                            <td><?php echo $activity->id; ?></td>
                                                            <td><?php echo $activity->verify_string; ?></td>
                                                            <td><?php echo $activity->agent_type; ?></td>     
                                                            <td><?php echo $activity->agent_string; ?></td>     
                                                            <td><?php echo $activity->object_type; ?></td>     
                                                            <td><?php echo $activity->object_string; ?></td>     
                                                            <td><?php echo $activity->activity; ?></td>     
                                                            <td><?php echo $activity->details; ?></td>
                                                            <td><?php echo formatDate($activity->created) ?></td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                <?php
                                    } else {
                                        echo $activities->message;
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
