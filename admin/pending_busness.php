<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout.php");
    }
    if($session->user_type != "admin"){
        redirect_to("logout.php");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $verify_string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_business_by_string_api.php?string=".$verify_string;
    $businesses = perform_get_curl($gurl);
    if($businesses){
        if($businesses->status == "success"){
            $business = $businesses->data;
            
            $cgurl = "fetch_business_categories_api.php?string=".$business->verify_string;
            $categories = perform_get_curl($cgurl);
            if($categories){
                
            } else {
                die("Categories Link Broken");
            }
        } else {
            die($businesses->message);
        }
    } else {
        die("Business Link Broken");
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
                        <li class="breadcrumb-item"><a href="pending_businesses">Pending Businesses</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $business->name; ?></a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"><?php echo $business->name; ?></h4>
                            <?php echo output_message($message); ?>
                        </div>
                        <div class="card-body card-block text-dark">
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Business Description</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo $business->description; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Business Logo</strong></div>
                                <div class="col-lg-9 col-md-8"><?php
                                    if(file_exists("../images/{$business->filename}.jpg")){
                                ?>
                                        <img src="https://yenreach.com/images/<?php echo $business->filename.".jpg"; ?>" style="width: 300px; max-width: 80%; height: auto;">
                                <?php
                                    }
                                ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Phone Number</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo $business->phonenumber; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Email Address</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo $business->email; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Business Categories</strong></div>
                                <div class="col-lg-9 col-md-8"><?php
                                    if($categories->status == "success"){
                                        foreach($categories->data as $category){
                                            echo '<span class="pr-3">'.$category->category.'</span>';
                                        }
                                    }
                                ?></div>
                            </div>    
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Business Address</strong></div>
                                <div class="col-lg-9 col-md-8"><?php
                                    echo $business->address.", <br />";
                                    if(!empty($business->town)){
                                        echo $business->town.", ";
                                    }
                                    if(!empty($business->lga)){
                                        echo $business->lga." Local Government Area,<br />";
                                    }
                                    echo $business->state." State.";
                                ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label"><strong>Date Business Started</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo classify_month($business->month_started)." ".$business->year_started; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <a href="business_approve?<?php echo $business->verify_string; ?>" class="btn btn-success"
                                    onclick="return confirm('Do you really want to Approve this Business?')">Approve</a>
                                </div>
                                <div class="col-4">
                                    <a href="business_disapprove?<?php echo $business->verify_string; ?>" class="btn btn-danger"
                                    onclick="return confirm('Do you really want to disapprove this Business?')">Disapprove</a>
                                </div>
                                <div class="col-4">
                                    <a href="edit_pending_business?<?php echo $business->verify_string; ?>" class="btn btn-warning">Edit Business Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>