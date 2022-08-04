<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $gurl = "fetch_all_categories_api.php";
    $categories = perform_get_curl($gurl);
    if($categories){
        
    } else {
        die("Categories Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Sections</h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">All Categories</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Categories</h4>
                        </div>
                        <div class="card-body card-block text-dark">
                            <div class="table-responsive text-dark">
                                <?php
                                    if($categories->status == "success"){
                                ?>
                                        <table class="table table-striped table-bordered text-dark" id="all_categories">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Section</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($categories->data as $category){
                                                ?>
                                                        <tr>
                                                            <td><?php echo $category->category ?></td>
                                                            <td><a href="section?string=<?php echo $category->section_string; ?>" class="text-primary"><?php echo $category->section; ?></a></td>
                                                            <td><a href="category?category=<?php echo $category->verify_string; ?>" class="btn btn-primary">More Details</a></td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                <?php
                                    } else {
                                        echo '<p>'.$categories->message.'</p>';
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