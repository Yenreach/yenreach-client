<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout.php");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout.php");
    }
    
    $string = !empty($_GET['string']) ? (string)$_GET['string'] : "";
    $gurl = "fetch_category_by_string_api.php?string=".$string;
    $categorys = perform_get_curl($gurl);
    if($categorys){
        if($categorys->status == "success"){
            $category = $categorys->data;
            
            if(isset($_POST['submit'])){
                $categ = !empty($_POST['category']) ? (string)$_POST['category'] : "";
                $details = !empty($_POST['details']) ? (string)$_POST['details'] : "";
                $section_string = !empty($_POST['section_string']) ? (string)$_POST['section_string'] : "";
                
                $purl = "update_category_api.php";
                $pdata = [
                        'verify_string' => $category->verify_string,
                        'category' => $categ,
                        'details' => $details,
                        'section_string' => $section_string
                    ];
                $categ_update = perform_post_curl($purl, $pdata);
                if($categ_update){
                    if($categ_update->status == "success"){
                        $update = $categ_update->data;
                        
                        $lpurl = "add_activity_log_api.php";
                        $lpdata = [
                                'agent_type' => $session->user_type,
                                'agent_string' => $session->verify_string,
                                'object_type' => "Category",
                                'object_string' => $update->verify_string,
                                "activity" => "Update",
                                "details" => "{$update->category} was edited"
                            ];
                        perform_post_curl($lpurl, $lpdata);
                        
                        $session->message("Category Edit was successful");
                        redirect_to("section?string=".$section_string);
                    } else {
                        $message = $categ_update->message;
                    }
                } else {
                    $message = "Category Update Link Broken";
                }
            } else {
                $section_string = $category->section_string;
                $categ = $category->category;
                $details = $category->details;
            }
            
            $sgurl = "fetch_all_sections_api.php";
            $sections = perform_get_curl($sgurl);
            if($sections){
                if($sections->status == "success"){
                    
                } else {
                    die($sections->message);
                }
            } else {
                die("Sections Link Broken");
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
                        <li class="breadcrumb-item"><a href="sections">Sections</a></li>
                        <li class="breadcrumb-item"><a href="section?string=<?php echo $category->section_string; ?>"><?php echo $category->section; ?></a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?php echo $category->category; ?></a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Category</h4>
                            <?php echo output_message($message); ?>
                        </div>
                        <div class="card-body">
                            <form action="category_edit?string=<?php echo $string; ?>" method="POST">
                                <div class="form-group">
                                    <select name="section_string" id="cat_section" class="form-control">
                                        <option value="">--Section--</option>
                                        <?php
                                            foreach($sections->data as $section){
                                                echo '<option value="'.$section->verify_string.'"';
                                                if($section->verify_string == $section_string){
                                                    echo ' selected';
                                                }
                                                echo '>'.$section->section.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="category" id="cat_category" class="form-control" placeholder="Category" value="<?php echo $categ; ?>">
                                </div>
                                <div class="form-group">
                                    <textarea name="details" row="5" id="cat_details" class="form-control" placeholder="Details"><?php echo $details; ?></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>