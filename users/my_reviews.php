<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("auth");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    $gurl = "fetch_user_reviews_api.php?string=".$session->verify_string;
    $reviews = perform_get_curl($gurl);
    if($reviews){
        
    } else {
        die("Business Reviews Link Broken");
    }
    
    include_portal_template("header.php");
?>

    <main id="main" class="main">
        <div class="row">
            <div class="container">
                <!-- ======= Breadcrumbs ======= -->
                <section class="breadcrumbs">
                    <div class="container">
                        <ol>
                            <li><a href="dashboard">Dashboard</a></li>
                            <li>My Reviews</li>
                        </ol>
                        <h2>Business Reviews</h2>
                        <p>These are your Reviews on Businesses</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <?php
            if($reviews->status == "success"){
        ?>
                <div class="row">
                    <div class="col-12">
                       <div class="card">
                           <div class="card-header">
                               <h4 class="card-title">Reviews</h4>
                           </div>
                           <div class="card-body card-block">
                               <div class="table-responsive text-dark">
                                    <table class="table table-responsive-sm table-striped text-dark" id="reviews_table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Business</th>
                                                <th>Rating</th>
                                                <th>Review</th>
                                                <th></th>
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
                                                        $rates .= '<i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>';   
                                                    }
                                                    if($bland > 0){
                                                        for($t=1; $t<=$bland; $t++){
                                                            $rates .= '<i class="bi bi-star-fill" id="1"></i>';
                                                        }
                                                    }
                                            ?>
                                                    <tr>
                                                        <td><?php echo $day; ?></td>
                                                        <td><?php echo $review->business; ?></td>
                                                        <td><?php echo $rates; ?></td>
                                                        <td><?php echo $review->review; ?></td>
                                                        <td><a href="edit_review?<?php echo $review->verify_string; ?>" class="text-primary">Edit Review</a></td>
                                                        <td><a href="delete_review?<?php echo $review->verify_string; ?>" class="text-danger"
                                                        onclick="return confirm('Do you really want to delete this Review?')">Delete Review</a></td>
                                                    </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            } else {
                echo $reviews->message;
            }
        ?>
    </main>

<?php include_portal_template("footer.php"); ?>