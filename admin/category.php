<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $string = !empty($_GET['category']) ? (string)$_GET['category'] : "";
    $gurl = "fetch_category_by_string_api.php?string=".$string;
    $categorys = perform_get_curl($gurl);
    if($categorys){
        if($categorys->status == "success"){
            $category = $categorys->data;
            
            $bgurl = "fetch_all_businesses_by_category_api.php?string=".$string;
            $businesses = perform_get_curl($bgurl);
            if($businesses){
                
            } else {
                die("Category Businesses Link Broken");
            }
        } else {
            die($categorys->message);
        }
    } else {
        die("Category Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Category</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="categories">Categories</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $category->category; ?></a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"><?php echo $category->category; ?></h4>
                        </div>
                        <div class="card-body card-block text-dark">
                            <p>
                                <strong>Details</strong>
                                <br />
                                <?php echo $category->category; ?>
                            </p>       
                            <p>
                                <a href="category_edit?string=<?php echo $category->verify_string; ?>" class="btn btn-primary">Edit</a>
                                <a href="category_delete?string=<?php echo $category->verify_string; ?>" class="btn btn-danger"
                                onclick="return confirm('Do you really want to delete this Category')">Delete</a>
                            </p>
                            <h4><small>Businesses</small></h4>
                            <div class="table-responsive text-dark">
                                <?php
                                    if($businesses->status == "success"){
                                ?>
                                        <table class="table table-striped table-bordered text-dark" id="businesses_list">
                                            <thead>
                                                <tr>
                                                    <th>Business Name</th>
                                                    <th>State</th>
                                                    <th>Business Owner</th>
                                                    <th>Date Registered</th>
                                                    <th>Registration Stage</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($businesses->data as $business){
                                                ?>
                                                        <tr>
                                                            <td><?php echo $business->name; ?></td>
                                                            <td><?php echo $business->state." State"; ?></td>
                                                            <td><a href="user?<?php echo $business->user_string ?>/<?php echo $business->owner_name.".html"; ?>" class="text-primary"><?php
                                                                echo $business->owner_name;
                                                            ?></a></td>
                                                            <td><?php echo strftime("%A, %d %B %Y %I:%M:%S%p", $business->created); ?></td>
                                                            <td><?php
                                                                if($business->reg_stage == 0){
                                                                    echo '<span class="text-danger  rounded">Suspended/Deactivated</span>';
                                                                } elseif($business->reg_stage < 3){
                                                                    echo '<span class="text-warning   rounded ">Incomplete Registration</span>';
                                                                } elseif($business->reg_stage == 3){
                                                                    echo '<span class="text-primary  rounded">Pending Approval</span>';
                                                                } elseif($business->reg_stage == 4){
                                                                    echo '<span class="text-success  rounded">Approved</span>';
                                                                }
                                                            ?></td>
                                                            <td><a href="business?<?php echo $business->verify_string; ?>" class="text-primary">More Details</a></td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                <?php
                                    } else {
                                        echo '<p>'.$businesses->message.'</p>';
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