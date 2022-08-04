<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("auth");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    if(!$session->is_business_logged()){
        redirect_to("dashboard");
    }
    
    $gurl = "fetch_business_reviews_api.php?string=".$session->business_string;
    $reviews = perform_get_curl($gurl);
    if($reviews){
        
    } else {
        die("Business Reviews Link Broken");
    }
    
    $sgurl = "fetch_business_review_summary_api.php?string=".$session->business_string;
    $summarys = perform_get_curl($sgurl);
    if($summarys){
        
    } else {
        die("Business Review Summary Link Broken");
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
                            <li>Business Reviews</li>
                        </ol>
                        <h2>Business Reviews</h2>
                        <p>What People are saying about this Business</p>
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
                                <?php
                                    if($summarys->status == "success"){
                                        $summary = $summarys->data;
                                ?>
                                    <div class="row">
                                        <p>A total of <?php echo $summary->total ?> Review<?php if($summary->total > 1){ echo 's'; } ?></p>
                                        <p>
                                            <strong>Average Rating: </strong>
                                            <?php
                                                $blanded = 5 - $summary->average;
                                                $rating = "";
                                                for($e=1; $e<=$summary->average; $e++){
                                                    $rating .= '<i class="bi bi-star-fill" style="color: #e1ad01"></i>';
                                                }
                                                if($blanded > 0){
                                                    for($r=1; $r<=$blanded; $r++){
                                                        $rating .= '<i class="bi bi-star-fill"></i>';
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
                                    <table class="table table-responsive-sm table-striped text-dark" id="reviews_table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Name</th>
                                                <th>Rating</th>
                                                <th>Review</th>
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
                                                        <td><?php echo $review->user; ?></td>
                                                        <td><?php echo $rates; ?></td>
                                                        <td><?php echo $review->review; ?></td>
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