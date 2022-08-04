<?php
    require_once("../../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    if(!$session->is_logged_in()){
        redirect_to("auth?page={$url}");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_billboard_application_by_string_api.php?string={$string}";
    $applications = perform_get_curl($gurl);
    if($applications){
        if($applications->status == "success"){
            $application = $applications->data;
            $advert = $application->advert_type;
        } else {
            die($applications->message);
        }
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
                            <li>Yenreach Billboard Applications</li>
                        </ol>
                        <h2>Yenreach Billboard Applications</h2>
                        <p>A list of all your Yenreach Billboard Applications</p>
                        
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Billboard Application</h4>
                        <?php echo output_message($message); ?>
                    </div>
                    <div class="card-body">
                        <div class="text-dark">
                            <table class="table table-responsive table-responsive-sm text-dark">
                                <tr>
                                    <th scope="row">Image</th>
                                    <td><img src="<?php echo "../images/{$application->filename}.jpg"; ?>" height="300px" width="auto" style="max-width:90%"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Headline</th>
                                    <td><?php echo $application->title; ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Description</th>
                                    <td><?php echo nl2br($application->text); ?></td>
                                </tr>
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
                                    <th scope="row">Advert Details</th>
                                    <td>
                                        <p>
                                            <h5>Advert Type</h5>
                                            <?php echo $advert->title; ?>
                                        </p>
                                        <p>
                                            <h5>Duration</h5>
                                            <?php
                                                echo $advert->duration." ";
                                                echo classify_duration($advert->duration);
                                                if($advert->duration > 1){
                                                    echo "s";
                                                }
                                            ?>
                                        </p>
                                        <p>
                                            <h5>Amount</h5>
                                            <?php "NGN".number_format($advert->amount, 2); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Start Date</th>
                                    <td><?php echo $application->start_date ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">End Date</th>
                                    <td><?php echo $application->end_date; ?></td>
                                </tr>
                            </table>
                        </div>
                        <?php
                            if($application->stage < 4){
                                $time = time();
                                $today = strftime("%Y-%m-%d", $time);
                                if(($application->stage == 3) && ($today <= $application->proposed_start_date)){
                        ?>
                                    <div class="mt-3 text-center">
                                        <p>
                                            In order to complete your application, you'll have to make a payment of <b>NGN<?php echo number_format($advert->amount, 2); ?></b>
                                        </p>
                                        <a href="billboard_payment?<?php echo $application->verify_string ?>" class="btn btn-primary">Make Payment</a>
                                    </div>
                        <?php
                                }
                        ?>
                                <div class="mt-3 text-center">
                                    <a href="billboard_application_edit?<?php echo $application->verify_string ?>" class="text-primary">Edit</a>
                                    &nbsp;
                                    <a href="billboard_application_delete?<?php echo $application->verify_string ?>" class="text-danger"
                                    onclick="return confirm('Do you really want to delete this Yenreach Billboard Application?')">Delete</a>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>