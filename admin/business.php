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
            
            $pgurl = "fetch_business_photos_api.php?string=".$business->verify_string;
            $photos = perform_get_curl($pgurl);
            if($photos){
                
            } else {
                die("Photos Link Broken");
            }
            
            $vgurl = "fetch_business_videolinks_api.php?string=".$business->verify_string;
            $links = perform_get_curl($vgurl);
            if($links){
                
            } else {
                die("Video Link Broken");
            }
            
            $sgurl = "fetch_business_subscriptions_api.php?string=".$business->verify_string;
            $subs = perform_get_curl($sgurl);
            if($subs){
                
            } else {
                die("Subscriptions Link Broken");
            }
            
            $wgurl = "fetch_business_working_hours_api.php?string=".$business->verify_string;
            $hours = perform_get_curl($wgurl);
            if($hours){
                
            } else {
                die("Working Hours Link Broken");
            }
            
            $bgurl = "fetch_business_branches_api.php?string=".$business->verify_string;
            $branches = perform_get_curl($bgurl);
            if($branches){
                
            } else {
                die("Branches Link Broken");
            }
            
            $gurl = "fetch_business_reviews_api.php?string=".$business->verify_string;
            $reviews = perform_get_curl($gurl);
            if($reviews){
                
            } else {
                die("Business Reviews Link Broken");
            }
            
            $sgurl = "fetch_business_review_summary_api.php?string=".$business->verify_string;
            $summarys = perform_get_curl($sgurl);
            if($summarys){
                
            } else {
                die("Business Review Summary Link Broken");
            }
        } else {
            die($businesses->message);
        }
    } else {
        die("Business Link Broken");
    }
    
    include_admin_template("header.php");
?>
    <style>
        .card-image{
              background-color: none;
              width: 100%;
              position: relative;
              padding-top: 100%;
            }
            
            .card-img {
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                background-size:cover;
                background-repeat: no-repeat;
                background-position: center center;
            }
    </style>
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Business</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Businesses</a></li>
                        <li class="breadcrumb-item"><a href="all_businesses">All Businesses</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $business->name; ?></a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Business Profile</h4>
                            <?php echo output_message($message); ?>
                        </div>
                        <div class="card-body card-block text-dark">
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4"><strong>Business Name</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo $business->name; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4"><strong>Business Description</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo $business->description; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4"><strong>Business Logo</strong></div>
                                <div class="col-lg-9 col-md-8"><?php
                                    if(file_exists("../images/{$business->filename}.jpg")){
                                ?>
                                        <img src="https://yenreach.com/images/<?php echo $business->filename.".jpg"; ?>" style="width: 300px; max-width: 80%; height: auto;">
                                <?php
                                    }
                                ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4"><strong>Phone Number</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo $business->phonenumber; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4"><strong>Email Address</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo $business->email; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4"><strong>Business Categories</strong></div>
                                <div class="col-lg-9 col-md-8"><?php
                                    if($categories->status == "success"){
                                        foreach($categories->data as $category){
                                            echo '<span class="pr-3">'.$category->category.'</span>';
                                        }
                                    }
                                ?></div>
                            </div>    
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4"><strong>Business Address</strong></div>
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
                                <div class="col-lg-3 col-md-4"><strong>Date Business Started</strong></div>
                                <div class="col-lg-9 col-md-8"><?php echo classify_month($business->month_started)." ".$business->year_started; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <a href="edit_business?string=<?php echo $business->verify_string; ?>" class="btn btn-warning">Edit Business Details</a>
                                </div>
                                
                                <div class="col-4">
                                    <a href="make_week_business?<?php echo $business->verify_string; ?>" class="btn btn-primary"
                                    onclick="return confirm('Do you really want to make this Business the Business of the Week?')">Make Business of the Week</a>
                                </div>
                                
                                <div class="col-4">
                                    <a href="delete_business?<?php echo $business->verify_string ?>" class="btn btn-danger"
                                    onlick="return confirm('Do you really want to delete this Business?')">Delete Business</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Business Working Hours</h4>
                        </div>
                        <div class="card-body card-block">
                            <div class="table-responsive text-dark">
                                <?php
                                    if($hours->status == "success"){
                                ?>
                                        <table class="table table-responsive-sm table-striped text-dark">
                                            <thead>
                                                <tr>
                                                    <th>Work Days</th>
                                                    <th>Work Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($hours->data as $hour){
                                                ?>
                                                        <tr>
                                                            <td><?php echo $hour->day ?></td>
                                                            <td><?php
                                                                if(!empty($hour->opening_time)){
                                                                    echo $hour->opening_time;
                                                                    if(!empty($hour->closing_time)){
                                                                        echo " - ".$hour->closing_time;
                                                                    }
                                                                } else {
                                                                    echo $hour->timing;
                                                                }
                                                            ?></td>
                                                            <td><a href="edit_working_hour?<?php echo $hour->verify_string; ?>" class="text-primary">Edit</a></td>
                                                            <td><a href="delete_working_hour?<?php echo $hour->verify_string; ?>" class="text-danger"
                                                            onclick="return confirm('Do you really want to delete this Work Day?')">Delete</a></td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                <?php
                                    } else {
                                        echo '<p>'.$hours->message.'</p>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Business Subscriptions</h4>
                        </div>
                        <div class="card-body card-block text-dark">
                            <?php
                                if($subs->status == "success"){
                            ?>
                                    <div class="table-responsive text-dark">
                                        <table class="table table-responsive-sm table-striped text-dark" id="subscription_table">
                                            <thead>
                                                <tr>
                                                    <th>Subscription</th>
                                                    <th>Date Started</th>
                                                    <th>Expiry Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($subs->data as $sub){
                                                ?>
                                                        <tr>
                                                            <td><a href="subscription_details?<?php echo $sub->subscription_string ?>/<?php echo $sub->subscription.".html"; ?>"
                                                            class="text-primary"><?php echo $sub->subscription; ?>
                                                            <br />(<?php echo $sub->payment_plan; ?>)</a></td>
                                                            <td><?php 
                                                                //echo strftime("%A, %d %B %Y; %I:%M:%S%p", $sub->started); 
                                                                echo strftime("%Y.%m.%d %H:%M:%S", $sub->started);
                                                            ?></td>
                                                            <td><?php 
                                                                //echo strftime("%A, %d %B %Y; %I:%M:%S%p", $sub->expired); 
                                                                echo strftime("%Y.%m.%d %H:%M:%S", $sub->expired);
                                                            ?></td>
                                                            <?php
                                                                $time = time();
                                                                if($time > $sub->expired){
                                                            ?>
                                                                    <td><span class="text-danger">Expired</span></td>
                                                            <?php
                                                                } else {
                                                                    if($sub->status != 1){
                                                                        if($sub->status == 2){
                                                            ?>
                                                                            <td><span class="text-warning">Renewed</span></td>
                                                            <?php
                                                                        } else {
                                                            ?>
                                                                            <td><span class="text-danger">Cancelled</span></td>
                                                            <?php
                                                                        }
                                                                    } else {
                                                            ?>
                                                                        <td><span class="text-success">Active</span>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                            <?php
                                } else {
                                    echo '<p>'.$subs->message.'</p>';
                                }
                            ?>    
                        </div>
                        <div class="card-footer">
                            <a href="subscribe_business?<?php echo $business->verify_string; ?>" class="btn btn-primary">Upgrade Business</a>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Business Branches</h4>
                        </div>
                        <div class="card-body card-block">
                            <div class="table-responsive text-dark">
                                <?php
                                    if($branches->status == "success"){
                                ?>
                                        <table class="table table-responsive-sm table-striped table0bordered text-dark">
                                            <thead>
                                                <tr>
                                                    <th>Address</th>
                                                    <th>Branch Head</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($branches->data as $branch){
                                                ?>
                                                        <tr>
                                                            <td><?php
                                                                echo nl2br($branch->address).",";
                                                                echo "<br />".$branch->town.", ".$branch->lga." LGA,";
                                                                echo "<br />".$branch->state;
                                                            ?></td>
                                                            <td><?php
                                                                echo $branch->head_name."<br />";
                                                                echo "({$branch->head_designation})";
                                                            ?></td>
                                                            <td><?php echo $branch->phone; ?></td>
                                                            <td><?php echo $branch->email; ?></td>
                                                            <td><a href="edit_business_branch?<?php echo $branch->verify_string; ?>" class="text-primary">Edit Branch</a></td>
                                                            <td><a href="delete_business_brach?<?php echo $branch->verify_string; ?>" class="text-danger"
                                                            onclick="return confirm('Do you really want to delete this Branch?')">Delete Branch</a></td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                <?php
                                    } else {
                                        echo '<p>'.$branches->message.'</p>';
                                    }
                                ?>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"></h4>
                        </div>
                        <div class="card-body">
                            <?php
                                if($photos->status == "success"){
                            ?>
                                    <div class="row">
                                        <?php
                                            foreach($photos->data as $photo){
                                        ?>
                                                <div class="col-12 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                     <div class="card">
                                                         <div class="card-image">
                                                             <div class="card-img" style="background-image: url(https://yenreach.com/<?php echo $photo->filepath; ?>)">&nbsp;</div>
                                                         </div>
                                                         <div class="card-footer">
                                                             <a href="change_business_photo?<?php echo $photo->verify_string ?>" class="btn btn-primary mb-2">Change</a>
                                                             <a href="delete_business_photo?<?php echo $photo->verify_string ?>" class="btn btn-danger mb-2"
                                                             onclick="return confirm('Do you really want to delete this Photo?')">Delete</a>
                                                         </div>
                                                     </div>
                                                 </div>   
                                        <?php
                                            }
                                        ?>
                                    </div>
                            <?php
                                } else {
                                    echo '<p class="text-dark">'.$photos->message.'</p>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Business Videos</h4>
                        </div>
                        <div class="card-body card-block">
                            <div class="table-responsive text-dark">
                                <?php
                                    if($links->status == "success"){
                                ?>
                                        <table class="table table-responsive-sm table-striped text-dark">
                                            <thead>
                                                <tr>
                                                    <th>Video</th>
                                                    <th>Platform</th>
                                                    <th>Link</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($links->data as $link){
                                                ?>
                                                        <tr>
                                                            <td><?php
                                                                if($link->platform == "YouTube"){
                                                            ?>
                                                                    <iframe height="200" width="auto" src="<?php echo $link->video_link; ?>" title="YouTube video player" 
                                                                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                                    allowfullscreen></iframe>
                                                            <?php
                                                                }
                                                            ?></td>
                                                            <td><?php echo $link->platform; ?></td>
                                                            <td><?php echo $link->real_link; ?></td>
                                                            <td><a href="change_videolink?<?php echo $link->verify_string; ?>" class="text-primary">Change</a></td>
                                                            <td><a href="delete_videolink?<?php echo $link->verify_string; ?>" class="text-danger"
                                                            onclick="return confirm('Do you really want to delete this Video Link?')">Delete</a></td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                <?php
                                    } else {
                                        echo '<p>'.$links->message.'</p>';
                                    }
                                ?>    
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Reviews</h4>
                        </div>
                        <div class="card-body card-block">
                            <?php
                                if($summarys->status == "success"){
                                    $summary = $summarys->data;
                            ?>
                                <div class="row text-dark">
                                    <p class="col-12">A total of <?php echo $summary->total ?> Review<?php if($summary->total > 1){ echo 's'; } ?></p>
                                    <p class="col-12">
                                        <strong>Average Rating: </strong>
                                        <?php
                                            $blanded = 5 - $summary->average;
                                            $rating = "";
                                            for($e=1; $e<=$summary->average; $e++){
                                                $rating .= '<i class="fa fa-star" style="color: #e1ad01"></i>';
                                            }
                                            if($blanded > 0){
                                                for($r=1; $r<=$blanded; $r++){
                                                    $rating .= '<i class="fa fa-star"></i>';
                                                }
                                            }
                                            echo $rating;
                                        ?>
                                    </p>
                                </div>    
                            <?php
                                }
                            ?>
                            <div class="table-responsive text-dark">
                                <?php
                                    if($reviews->status == "success"){
                                ?>
                                        <table class="table table-responsive-sm table-striped text-dark" id="reviews_table">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Name</th>
                                                    <th>Rating</th>
                                                    <th>Review</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($reviews->data as $review){
                                                        $day = strftime("%Y.%m.%d %H:%M:%S", $review->created);
                                                        $colored = $review->star;
                                                        $bland = 5 - $colored;
                                                        $rates = "";
                                                        for($i=1; $i<=$colored; $i++){
                                                            $rates .= '<i class="fa fa-star" style="color: #e1ad01"></i>';   
                                                        }
                                                        if($bland > 0){
                                                            for($t=1; $t<=$bland; $t++){
                                                                $rates .= '<i class="fa fa-star"></i>';
                                                            }
                                                        }
                                                ?>
                                                        <tr>
                                                            <td><?php echo $day; ?></td>
                                                            <td><?php echo $review->user; ?></td>
                                                            <td><?php echo $rates; ?></td>
                                                            <td><?php echo $review->review; ?></td>
                                                            <td><a href="delete_review?<?php echo $review->verify_string; ?>" class="text-danger"
                                                            onclick="return confirm('Do you really want to delete this Review?')">Delete Review</a></td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                <?php
                                    } else {
                                        echo $reviews->message;
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