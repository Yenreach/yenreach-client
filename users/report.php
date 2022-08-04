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
    
    if(isset($_POST['submit'])){
        $date_from = !empty($_POST['date_from']) ? (string)$_POST['date_from'] : "";
        $date_to = !empty($_POST['date_to']) ? (string)$_POST['date_to']: "";
        
        $gurl = "fetch_business_report_summary_search_api.php?string=".$session->business_string."&from=".$date_from."&date_to=".$date_to;
        $summarys = perform_get_curl($gurl);
        if($summarys){
            if($summarys->status == "success"){
                $dgurl = "fetch_business_daily_report_summary_search_api.php?string=".$session->business_string."&from=".$date_from."&date_to=".$date_to;
                $details = perform_get_curl($dgurl);
                if($details){
                    if($details->status == "success"){
                        
                    }
                } else {
                    die("Daily Details Link Broken");
                }
            }
        } else {
            die("Report Summary Link Broken");
        }
    } else {
        $date_from = "";
        $date_to = "";
        $gurl = "fetch_business_report_summary_api.php?string=".$session->business_string;
        $summarys = perform_get_curl($gurl);
        if($summarys){
            if($summarys->status == "success"){
                $dgurl = "fetch_business_daily_report_summary_api.php?string=".$session->business_string;
                $details = perform_get_curl($dgurl);
                if($details){
                    if($details->status == "success"){
                        
                    } else {
                        die($details->message);
                    }
                } else {
                    die("Daily Details Link Broken");
                }
            }
        } else {
            die("Report Summary Link Broken");
        }
    }
    
    include_portal_template('header.php');
?>

    <main id="main" class="main">
        <div class="row">
            <div class="container">
                <!-- ======= Breadcrumbs ======= -->
                <section class="breadcrumbs">
                    <div class="container">
                        <ol>
                            <li><a href="dashboard">Dashboard</a></li>
                            <li>Business Report</li>
                        </ol>
                        <h2>Business Report</h2>
                        <p>Statistical Report of your Page Visits</p>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        
        <?php
            if($summarys->status == "success"){
                $summary = $summarys->data;
        ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header"><h4 class="card-title">Filter</h4></div>
                            <div class="card-body">
                                <form action="report" role="form" method="POST" class="row g-3 needs-validation">
                                    <div class="col-md-6 mb-2">
                                        <label for="date_from" class="form-label mt-1">From</label>
                                        <div class="input-group has-validation">
                                            <input type="date" name="date_from" id="date_from" class="form-control" value="<?php echo $date_from; ?>" required>
                                            <div class="invalid-feedback">Please Provide a date</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="date_from" class="form-label mt-1">To</label>
                                        <div class="input-group has-validation">
                                            <input type="date" name="date_to" class="form-control" id="date_to" value="<?php echo $date_to; ?>" required>
                                            <div class="invalid-feedback">Please Provide a date</div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <button class="btn w-100 text-white py-2" style="background: #00C853;" id="report_submit" name="submit" type="submit">Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Summary</h4>
                            </div>
                            <div class="card-body card-block">
                                <?php
                                    if(isset($_POST['submit'])){
                                ?>
                                        This Business has a total of <?php echo $summary->visits; ?> visit<?php if($summary->visits > 1){ echo "s"; } ?>
                                        from a total of <?php echo $summary->people; echo $summary->people > 1 ? " People" : " Person"; ?> between 
                                        <?php 
                                            $from_string = strtotime($date_from);
                                            echo strftime("%d %B %Y", $from_string);
                                        ?> and <?php
                                            $to_string = strtotime($date_to);
                                            echo strftime("%d %B %Y", $to_string);
                                        ?>
                                <?php
                                    } else {
                                ?>
                                        This Business has a total of <?php echo $summary->visits; ?> visit<?php if($summary->visits > 1){ echo "s"; } ?>
                                        from a total of <?php echo $summary->people; echo $summary->people > 1 ? " People" : " Person"; ?>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    
                </div>
                
                <div class="row">
                    <div class="col-12">
                       <div class="card">
                           <div class="card-header">
                               <h4 class="card-title">Daily Summary</h4>
                           </div>
                           <div class="card-body card-block">
                               <div class="table-responsive text-dark">
                                    <table class="table table-responsive-sm table-striped text-dark" id="report_table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Total Persons</th>
                                                <th>Total Visits</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($details->data as $detail){
                                            ?>
                                                    <tr>
                                                        <td><?php
                                                            echo $detail->year.'-'.$detail->month.'-'.$detail->day;
                                                        ?></td>
                                                        <td><?php echo $detail->people; ?></td>
                                                        <td><?php echo $detail->visits; ?></td>
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
                echo '<p class="text-light">'.$summarys->message.'</p>';
            }
        ?>
                    
        
    </main>

<?php include_portal_template('footer.php'); ?>