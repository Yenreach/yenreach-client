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
    
    $gurl = "fetch_all_blog_post_api.php";
    $blogs = perform_get_curl($gurl);
    if($blogs){
        if($blogs->status == "success"){
            $page = !empty($exploded[1]) ? (int)$exploded[1] : 1;
            $per_page = 40;
            $total_count = count($blogs->data);
            
            $pagination = new Pagination($page, $per_page, $total_count);
            $blog_list = array_slice($blogs->data, $pagination->offset(), $per_page);
        } else {
            $blog_list = array();
        }
    } else {
        die("No Blogs Found");
    };

    // if(isset($_GET['submit_delete'])){
    //     $agurl = "delete_blog_post_api.php?blog_string=".$blog_string."admin_string=".$session->verify_string;
    //     $delete_blog = perform_get_curl($agurl);
    //     if($delete_blog){
    //         if($delete_blog->status == "success"){
    //             $delete_blog = $delete_blog->data;
    //         } else {
    //             die($delete_blog->message);
    //         }
    //     } else {
    //         die("Delete Blog Link Broken");
    //     }
    // } else {
    //     die("Something went wrong");
    // }

    function formatString($str) {
  
        $pattern = "/&lt;/i";
        $pattern2 = "/&gt;/i";
        $pattern3 = "/&nbsp;/i";
        $str = preg_replace($pattern, "<", $str);
        $str = preg_replace($pattern2, ">", $str);
        $str = preg_replace($pattern3, " ", $str);
        echo $str;
      };
  

    
    
    include_admin_template("header.php");
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 py-3">
                <div class="welcome-text">
                    <h4 class="font-weight-bolder">BLOGS</h4>
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
                if (!empty($blog_list)) { 
                    foreach($blog_list as $blog){  ?>
                    <div class='blog-card p-4 rounded'>
                        <div class='d-flex justify-content-between align-items-center mb-2'>
                                    <a href="edit_blog?<?php echo $blog->blog_string; ?>" class="btn btn-primary">Edit</a>
                                    <a href="delete_blog?<?php echo $blog->blog_string; ?>" class="btn btn-danger confirmation">Delete</a>
                        </div>
                        <a href="blog?<?php echo $blog->blog_string; ?>" class="d-flex flex-column p-0 rounded-top" style="color: black">
                            <div class='rounded-top d-flex flex-column justify-content-between blog-body'>
                                <div class=''>
                                    <div class=''>
                                        <span class="blog-title"><?php echo $blog->title; ?></span> - <span style="color: #bdbdc7; font-style:italic;"><?php echo $blog->author; ?></span>
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
<script>

    // console.log(text)
    const admin_string = <?php echo json_encode($session->verify_string); ?>;
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('This blogpost will be permanently deleted?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }

</script>
<?php include_admin_template("footer.php"); ?>