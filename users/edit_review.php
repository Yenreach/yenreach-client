<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("auth");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_review_by_string_api.php?string=".$string;
    $reviews = perform_get_curl($gurl);
    if($reviews){
        if($reviews->status == "success"){
            $review = $reviews->data;
            
            if(isset($_POST['submit'])){
                $review_text = !empty($_POST['review']) ? (string)$_POST['review'] : "";
                $star = !empty($_POST['rating']) ? (string)$_POST['rating'] : "";
                
                $purl = "update_review_api.php";
                $pdata = [
                        'verify_string' => $review->verify_string,
                        'review' => $review_text,
                        'star' => $star
                    ];
                
                $edit_review = perform_post_curl($purl, $pdata);
                if($edit_review){
                    if($edit_review->status == "success"){
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => "user",
                                'agent_string' => $session->verify_string,
                                'object_type' => "BusinessReviews",
                                'object_string' => $edit_review->data->verify_string,
                                "activity" => "Update",
                                "details" => "Review edited successfully"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        $session->message("Review Updated successfully");
                        redirect_to("my_reviews");
                    } else {
                        $message = $edit_review->message;
                    }
                } else {
                    $message = "Review Update Link Broken";
                }
            } else {
                $review_text = $review->review;
                $star = $review->star;
            }
        } else {
            die($reviews->message);
        }
    } else {
        die("Review Link Broken");
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
                            <li><a href="my_reviews">My Reviews</a></li>
                            <li>Edit Review</li>
                        </ol>
                        <h2>Business Reviews</h2>
                        <p>These are your Reviews on Businesses</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-success">Edit Review</h4>
                        <?php echo output_message($message); ?>
                    </div>
                    <div class="card-body card-block">
                        <form role="form" action="edit_review?<?php echo $string; ?>" method="POST" id="edit_review" class="row g-3 needs-validation">
                            <div class="col-12 mb-2">
                                <p>
                                    <strong>Business: </strong><?php echo $review->business; ?>
                                </p>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="bus_review" class="form-label mt-1">Review</label>
                                <div class="input-group has-validation">
                                    <textarea name="review" id="bus_review" class="form-control" placeholder="Business Review" rows="7" required><?php echo $review_text; ?></textarea>
                                    <div class="invalid-feedback">Please provide a Review</div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <p class="col-lg-3 col-md-4 col-sm-6">
                                    <input type="radio" name="rating" class="star" value="1" id="1" <?php if($star == 1){ echo "checked"; } ?> /><label for="1">
                                        <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                        <i class="bi bi-star-fill" id="2"></i>
                                        <i class="bi bi-star-fill" id="3"></i>
                                        <i class="bi bi-star-fill" id="4"></i>
                                        <i class="bi bi-star-fill" id="5"></i>
                                    </label>
                                </p>
                                <p class="col-lg-3 col-md-4 col-sm-6">
                                    <input type="radio" name="rating" class="star" value="2" id="2" <?php if($star == 2){ echo "checked"; } ?> /><label for="2">
                                        <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                        <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                        <i class="bi bi-star-fill" id="3"></i>
                                        <i class="bi bi-star-fill" id="4"></i>
                                        <i class="bi bi-star-fill" id="5"></i>
                                    </label>
                                </p>
                                <p class="col-lg-3 col-md-4 col-sm-6">
                                    <input type="radio" name="rating" class="star" value="3" id="3" <?php if($star == 3){ echo "checked"; } ?> /><label for="3">
                                        <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                        <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                        <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                        <i class="bi bi-star-fill" id="4"></i>
                                        <i class="bi bi-star-fill" id="5"></i>
                                    </label>
                                </p>
                                <p class="col-lg-3 col-md-4 col-sm-6">
                                    <input type="radio" name="rating" class="star" value="4" id="4" <?php if($star == 4){ echo "checked"; } ?> /><label for="4">
                                        <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                        <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                        <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                        <i class="bi bi-star-fill" id="4" style="color: #e1ad01"></i>
                                        <i class="bi bi-star-fill" id="5"></i>
                                    </label>
                                </p>
                                <p class="col-lg-3 col-md-4 col-sm-6">
                                    <input type="radio" name="rating" class="star" value="5" id="5" <?php if($star == 5){ echo "checked"; } ?> /><label for="5">
                                        <i class="bi bi-star-fill" id="1" style="color: #e1ad01"></i>
                                        <i class="bi bi-star-fill" id="2" style="color: #e1ad01"></i>
                                        <i class="bi bi-star-fill" id="3" style="color: #e1ad01"></i>
                                        <i class="bi bi-star-fill" id="4" style="color: #e1ad01"></i>
                                        <i class="bi bi-star-fill" id="5" style="color: #e1ad01"></i>
                                    </label>
                                </p>
                            </div>
                            <div class="col-12 mt-3">
                                <button class="btn w-100 text-white py-2" style="background: #00C853;" id="review_submit" name="submit" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>