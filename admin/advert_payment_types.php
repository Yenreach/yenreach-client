<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    if(isset($_POST['submit'])){
        $title = !empty($_POST['title']) ? (string)$_POST['title'] : "";
        $duration_type = !empty($_POST['duration_type']) ? (int)$_POST['duration_type'] : "";
        $duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : "";
        $amount = !empty($_POST['amount']) ? (double)$_POST['amount'] : "";
        
        $purl = "add_advert_payment_type_api.php";
        $pdata = $_POST;
        
        $add_advert = perform_post_curl($purl, $pdata);
        if($add_advert){
            if($add_advert->status == "success"){
                $advert = $add_advert->data;
                
                $lpurl = "add_activity_log_api.php";
                $lpdata = [
                        'agent_type' => $session->user_type,
                        'agent_string' => $session->verify_string,
                        'object_type' => "AdvertPayemntTypes",
                        'object_string' => $advert->verify_string,
                        "activity" => "Creation",
                        "details" => "{$title} added as an Advert Payment Type"
                    ];
                perform_post_curl($lpurl, $lpdata);
                
                $session->message($add->name." has been added as an Admin");
                redirect_to("advert_payment_types");
            } else {
                $message = $add_advert->message;
            }
        } else {
            $message = "Advert Payment Type Addition Link Broken";
        }
    } else {
        $title = "";
        $duration_type = "";
        $duration = "";
        $amount = "";
    }
    
    $gurl = "fetch_all_advert_payment_types_api.php";
    $types = perform_get_curl($gurl);
    if($types){
        
    } else {
        die("Advert Payment Types Link Broken");
    }
    
    include_admin_template("header.php");
?>


    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Advert Payment Types</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Components</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Advert Payment Types</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Advert Payment Types</h4>
                        </div>
                        <div class="card-body card-block">
                            <?php
                                if($types->status == "success"){
                            ?>
                                    <div class="table-responsive text-dark">
                                        <table class="table table-responsive-sm table-striped text-dark">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Duration</th>
                                                    <th scope="col">Amount(NGN)</th>
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($types->data as $type){
                                                ?>
                                                        <tr>
                                                            <td><?php echo $type->title; ?></td>
                                                            <td><?php
                                                                echo $type->duration." ";
                                                                echo classify_duration($type->duration_type);
                                                                if($type->duration > 1){
                                                                    echo "s";
                                                                }
                                                            ?></td>
                                                            <td><?php echo number_format($type->amount, 2); ?></td>
                                                            <td><a href="advert_payment_type_edit?<?php echo $type->verify_string; ?>" class="btn btn-primary">Edit</a></td>
                                                            <td><a href="advert_payment_type_delete?<?php echo $type->verify_string ?>" class="btn btn-danger text-light"
                                                            onclick="return confirm('Do you really want to delete this Advert Payment Type?')">Delete</a></td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                            <?php
                                } else {
                                    echo '<p class="text-dark">'.$types->message.'</p>';
                                }
                            ?>    
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add Advert Payment Type</h4>
                        </div>
                        <div class="card-body">
                           <form action="advert_payment_types" method="POST">
                                <div class="form-group">
                                   <input type="text" name="title" id="ad_title" class="form-control" placeholder="Type" value="<?php echo $title; ?>">
                                </div>
                                <div class="form-group">
                                   <select name="duration_type" id="ad_durationType" class="form-control">
                                       <option value="">--Duration Type--</option>
                                       <?php  trial_type($duration_type); ?>
                                   </select>
                                </div>
                                <div class="form-group">
                                   <input type="number" name="duration" id="ad_duration" class="form-control" placeholder="Duration" value="<?php echo $duration; ?>">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="amount" id="ad_amount" class="form-control" placeholder="Amount" value="<?php echo $amount ?>">
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