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
    $gurl = "fetch_advert_payment_type_by_string_api.php?string=".$string;
    $adverts = perform_get_curl($gurl);
    if($adverts){
        if($adverts->status == "success"){
            $advert = $adverts->data;
            
            if(isset($_POST['submit'])){
                $title = !empty($_POST['title']) ? (string)$_POST['title'] : "";
                $duration_type = !empty($_POST['duration_type']) ? (int)$_POST['duration_type'] : "";
                $duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : "";
                $amount = !empty($_POST['amount']) ? (double)$_POST['amount'] : "";
                
                $purl = "update_advert_payment_type_api.php";
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
                                "activity" => "Update",
                                "details" => "{$title} details updated"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        
                        $session->message($advert->title." details updated");
                        redirect_to("advert_payment_types");
                    } else {
                        $message = $add_advert->message;
                    }
                } else {
                    $message = "Advert Payment Type Addition Link Broken";
                }
            } else {
                $title = $advert->title;
                $duration_type = $advert->duration_type;
                $duration = $advert->duration;
                $amount = $advert->amount;
            }
        } else {
            die($adverts->message);
        }
    } else {
        die("Advert Payment Type Link Broken");
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
                        <li class=""
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Advert Payment Types</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Advert Payment Type</h4>
                        </div>
                        <div class="card-body">
                           <form action="advert_payment_type_edit?<?php echo $string; ?>" method="POST">
                                <input type="hidden" name="verify_string" value="<?php echo $string; ?>" />
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