<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout.php");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout.php");
    }
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    $gurl = "fetch_section_by_string_api.php?string=".$string;
    $sections = perform_get_curl($gurl);
    if($sections){
        if($sections->status == "success"){
            $section = $sections->data;
            
            if(isset($_POST['submit'])){
                $categ = !empty($_POST['category']) ? (string)$_POST['category'] : "";
                $details = !empty($_POST['details']) ? (string)$_POST['details'] : "";
                
                $purl = "add_category_api.php";
                $pdata = [
                        'section_string' => $section->verify_string,
                        'category' => $categ,
                        'details' => $details
                    ];
                
                $category_add = perform_post_curl($purl, $pdata);
                if($category_add){
                    if($category_add->status == "success"){
                        $add = $category_add->data;
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => $session->user_type,
                                'agent_string' => $session->verify_string,
                                'object_type' => "Category",
                                'object_string' => $add->verify_string,
                                "activity" => "Creation",
                                "details" => "{$add->category} has been added as a Category"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        
                        $session->message("{$add->category} was successfully added as a Category");
                        redirect_to("section?string=".$section->verify_string);
                    } else {
                        $message = $category_add->message;
                    }
                } else {
                    $message = "Category Adding Link Broken";
                }
            } else {
                $categ = "";
                $details = "";
            }
            
            $cgurl = "fetch_categories_by_section_string_api.php?section_string=".$section->verify_string;
            $categories = perform_get_curl($cgurl);
            if($categories){
                
            } else {
                die("Categories Link Broken");
            }
        } else {
            die($sections->message);
        }
    } else {
        die("Section Link Broken");
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
                        <li class="breadcrumb-item"><a href="sections">Sections</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $section->section; ?></a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"><?php echo $section->section; ?></h4>
                        </div>
                        <div class="card-body card-block text-dark">
                            <p>
                                <strong>Details</strong>
                                <br />
                                <?php echo $section->section; ?>
                            </p>       
                            <p>
                                <a href="section_edit?string=<?php echo $section->verify_string; ?>" class="btn btn-primary">Edit</a>
                                <a href="section_delete?string=<?php echo $section->verify_string; ?>" class="btn btn-danger"
                                onclick="return confirm('Please note that this delete this Section and every Category attached to it')">Delete</a>
                            </p>
                            <p class="mt-5">
                                <strong>Categories</strong>
                                <br />
                                <?php
                                    if($categories->status == "success"){
                                ?>
                                        <div class="table-responsive text-dark">
                                            <table class="table table-responsive-sm table-striped text-dark">
                                                <thead>
                                                    <tr>
                                                        <th>Category</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach($categories->data as $category){
                                                    ?>
                                                            <tr>
                                                                <td><?php echo $category->category; ?></td>
                                                                <td><a href="category?category=<?php echo $category->verify_string; ?>" class="btn btn-primary">More Details</a></td>
                                                            </tr>
                                                    <?php
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                <?php
                                    } else {
                                        echo $categories->message;
                                    }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Add Category</h5>
                            <?php echo output_message($message); ?>
                        </div>
                        <div class="card-body card-block text-dark">
                            <form action="section?string=<?php echo $string ?>" method="POST">
                                <div class="form-group">
                                    <input type="text" name="category" id="cat_category" class="form-control" placeholder="Category" value="<?php echo $categ; ?>">
                                </div>
                                <div class="form-group">
                                    <textarea name="details" id="cat_details" rows="5" class="form-control" placeholder="Details"><?php echo $details; ?></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" name="submit" id="cat_submit" class="btn btn-primary btn-block">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>