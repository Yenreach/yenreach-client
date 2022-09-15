<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("login");   
    }
    if($session->user_type != "admin"){
        redirect_to("logout");
    }

    
    $agurl = "fetch_admin_by_string_api.php?string=".$session->verify_string;
    $admins = perform_get_curl($agurl);
    if($admins){
        if($admins->status == "success"){
            $admin = $admins->data;
        } else {
            die($admins->message);
        }
    } else {
        die("Admin Link Broken");
    }
    
    $gurl = "fetch_all_email_sequence_api.php";
    $emails = perform_get_curl($gurl);
    if($emails){
        if($emails->status == "success"){
            $page = !empty($exploded[1]) ? (int)$exploded[1] : 1;
            $per_page = 40;
            $total_count = count($emails->data);
            
            $pagination = new Pagination($page, $per_page, $total_count);
            $email_list = array_slice($emails->data, $pagination->offset(), $per_page);
        } else {
            $email_list = array();
        }
    } else {
        die("No Emails Found");
    };
  
    
    include_admin_template("header.php");
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 py-3">
                <div class="welcome-text">
                    <h4 class="font-weight-bolder">EMAILS</h4>
                    <!-- <p class="mb-0">Make a blog post today</p> -->
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)"><i class="fa fa-home"></i></a></li>
                </ol>
            </div>
        </div>
                
            
        <div class="d-flex flex-column py-3 gap-1" style="background: #f8f9fe; gap:1.5rem;">
            <?php
                if (!empty($email_list)) { 
                    foreach($email_list as $email){  ?>
                    <div class='blog-card p-4 rounded'>
                        <div class='d-flex justify-content-between align-items-center mb-2'>
                                    <a href="edit_email?<?php echo $email->id; ?>" class="btn btn-primary">Edit</a>
                        </div>
                        <a href="edit_email?<?php echo $email->id; ?>" class="d-flex flex-column p-0 rounded-top" style="color: black">
                            <div class='rounded-top d-flex flex-column justify-content-between blog-body'>
                                <div class=''>
                                    <div class=''>
                                        <span class="blog-title"><?php echo $email->title; ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php }
            }; ?>
        </div>  
    </div>
</div>
<?php include_admin_template("footer.php"); ?>