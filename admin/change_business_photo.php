<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }
    
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $string = isset($exploded[1]) ? (string)$exploded[1] : "";
    $gurl = "fetch_business_photo_by_string_api.php?string=".$string;
    $photos = perform_get_curl($gurl);
    if($photos){
        if($photos->status == "success"){
            $picture = $photos->data;
            
            if(isset($_POST['submit'])){
                $file = !empty($_FILES['file']) ? $_FILES['file'] : array();
                if(!empty($file)){
                    if(($file['type'] == "image/jpg") || ($file['type'] == "image/jpeg") || ($file['type'] == "image/png") || ($file['type'] == "image/png")){
                        if(($file['size'] <= 204800) && ($file['size'] > 0)){
                            $purl = "update_business_photo_api.php";
                            $pdata = [
                                    'verify_string' => $picture->verify_string,
                                    'size' => $file['size']
                                ];
                            $add_photo = perform_post_curl($purl, $pdata);
                            if($add_photo){
                                if($add_photo->status == "success"){
                                    $add = $add_photo->data;
                                    
                                    $photo = new Photo();
                                    if(file_exists("../images/{$add->old_filename}.jpg")){
                                        $photo->destroy($add->old_filename);
                                    }
                                    if(file_exists("../images/thumbnails/{$add->old_filename}.jpg")){
                                        $photo->destroy_thumbnail($add->old_filename);
                                    }
                                    $photo->filename = $add->filename;
                                    $photo->load($file['tmp_name']);
                                    if($photo->save_compressed()){
                                        $photo->scale(70);
                                        $photo->save_thumbnail("jpg", 50, null);   
                                        
                                        $lpurl = "add_activity_log_api.php";
                                        $lpdata = [
                                                'agent_type' => "admin",
                                                'agent_string' => $session->verify_string,
                                                'object_type' => "Businesses",
                                                'object_string' => $add->business_string,
                                                "activity" => "Update",
                                                "details" => "Business Photo was changed"
                                            ];
                                        perform_post_curl($lpurl, $lpdata);
                                        $session->message("Photo Change was successful");
                                        redirect_to("business?".$add->business_string);
                                    } else {
                                        $message = "Wrong File Format";
                                    }
                                } else {
                                    $message = $add_photo->message;
                                }
                            } else {
                                $message = "Add Photo Link Broken";
                            }
                        } else {
                            $message = "File Size must not be more than 200KB";
                        }
                    } else {
                        $message = "Wrong File Format";
                    }
                } else {
                    $message = "No file was uploaded";
                }
            }
        } else {
            die($photos->message);
        }
    } else {
        die("Photos Link Broken");
    }
    
    include_admin_template("header.php");
?>

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4><?php echo $picture->business; ?></h4>
                        <span class="ml-1"><?php echo output_message($message); ?></span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Businesses</a></li>
                        <li class="breadcrumb-item"><a href="all_businesses">All Businesses</a></li>
                        <li class="breadcrumb-item"><a href="business?<?php echo $picture->business_string; ?>"><?php echo $picture->business; ?></a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Change Photo</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Change Business Photo</h4>
                        </div>
                        <div class="card-body card-block">
                            <form action="change_business_photo?<?php echo $string; ?>" enctype="multipart/form-data" method="POST">
                                <div class="form-group">
                                    <label class="form-label" for="file_upload">Photo</label>
                                    <p>
                                        The Photo should not be more than 200KB. We further recommend that the aspect ratio of the Photo should be 1:1
                                        (This means the height and the width of the Photo should be the same length).The Image should also be in any of 
                                        JPG, JPEG, PNG or GIF format
                                    </p>
                                    <input type="file" accept="image/jpg, image/jpeg, image/png, image/gif" id="file_upload" class="form-control" name="file" required>
                                </div>
                                <div class="form-group">
                                    <div class="text-center">
                                        <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_admin_template("footer.php"); ?>