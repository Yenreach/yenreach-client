<?php
    require_once("../../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    if(!$session->is_logged_in()){
        redirect_to("auth?page={$url}");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    $gurl = "fetch_all_advert_payment_types_api.php";
    $types = perform_get_curl($gurl);
    if($types){
        
    } else {
        die("Advert Payment Types Link Broken");
    }
    
    $bgurl = "fetch_billboard_applications_by_user_api.php?user_string=".$session->verify_string;
    $applications = perform_get_curl($bgurl);
    if($applications){
        
    } else {
        die("Billboard Applications Link Broken");
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
                            <li>Yenreach Billboard</li>
                        </ol>
                        <h2>Yenreach Billboard</h2>
                        <p>Advertise on Yenreach Online Billboard</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9 col-md-6 col-sm-6 mx-auto mx-sm-0">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">Get Much More Eyeballs, Get MUCH MORE SALES</h4>
                    </div>
                    <div class="card-body pt-3">
                        <p>
                            The only way your business can survive is by getting the right attention in the right place, at the right time and to the right people.
                        </p>
                        <p>
                            By showcasing your business on our homepage billboard, you get the ultimate advantage of putting your business in front of thousands of customers who might just be searching for your product or service and are ready to do business with you, RIGHT-AWAY.
                        </p>
                        <p>
                            Here are some of the things you enjoy just by having your business displayed on our Yenreach billboard: 
                            <ul>
                                <li>Drive traffic directly to your Yenreach page so customers can contact you with ease.</li>
                                <li>Build massive brand awareness</li>
                                <li>Showcase your business to thousands of our daily website visitors</li>
                                <li>Get your business displayed 24 hours a day and gain <u>STEADY</u> visibility and eyeballs that can turn into cash.</li>
                                <li>Reach a wider demographic of potential customers.</li>
                                <li>Build trust and credibility among potential customers</li>
                            </ul>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9 col-md-6 col-sm-6 mx-auto mx-sm-0">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-center">What do I need?</h4>
                    </div>
                    <div class="card-body pt-3">
                        Getting your Business or any other interest displayed on the Yenreach Billboard is not actually as difficult as you think. All you need are need the following:
                        <ol>
                            <li>An Image to display. This Image has to be in either JPG, JPEG or PNG format. It must not be more than 300KB. Furthermore it should not
                            contain any explicit or gory content. Furthermore, it is advisable that the Aspect Ratio of your Picture's resolution should be ...</li>
                            <li>Get a catchy Text/Copy for your Advert.</li>
                            <li>You'll also need a link that when the Viewer clicks on, it will lead them to a page where they'll learn more about the Ad</li>
                            <li>Please, it is highly important that your link or text or image should not suggest any form of unscrupulous activity. Yenreach reserves the right to take down
                            any Advert that does not meet our requirememnts</li>
                            <li>Also note that the slots for Billboard are limited. So, there is a possibility that your advert application may not be approved immediately.</li>
                        </ol>
                        <p>
                            Pick an Advert Package to begin your Application
                        </p>
                        
                        <?php
                            if($types->status == "success"){
                        ?>
                                <div class="row">
                                    <?php
                                        foreach($types->data as $type){
                                    ?>
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title"><?php echo $type->title; ?></h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <h4 style="font-size: 1.5rem; font-weight:bold" class="text-success py-2"><?php echo "NGN".number_format($type->amount, 2); ?></h4>
                                                        <h5 class="text-muted">
                                                            <?php
                                                                echo $type->duration." ";
                                                                echo classify_duration($type->duration_type);
                                                                if($type->duration > 1){
                                                                    echo "s";
                                                                }
                                                            ?>
                                                        </h5>
                                                    </div>
                                                    <div class="card-footer">
                                                        <a href="billboard_application?<?php echo $type->verify_string ?>" class="btn btn-success">Apply</a>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                        <?php
                            } else {
                                echo '<p>'.$types->message.'</p>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="my_applications">
            <div class="col-md-9 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Yenreach Billboard Applications</h4>
                        <?php echo output_message($message); ?>
                    </div>
                    <div class="card-body card block">
                        <div class="table-responsive text-dark">
                            <?php
                                if($applications->status == "success"){
                            ?>
                                    <table class="table table-striped table-responsive-sm text-dark" id="applications_table">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Heading</th>
                                                <th>Date Applied</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($applications->data as $application){
                                            ?>
                                                    <tr>
                                                        <td><?php echo $application->code; ?></td>
                                                        <td><?php echo $application->title; ?></td>
                                                        <td><?php echo strftime("%Y.%m.%d %H.%M.%S", $application->created); ?></td>
                                                        <td><?php echo application_status($application->stage); ?></td>
                                                        <td><a href="billboard_applied?<?php echo $application->verify_string; ?>" class="text-primary">Details</a></td>
                                                    </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                            <?php
                                } else {
                                    echo '<p>'.$applications->message.'</p>';
                                }
                            ?>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>