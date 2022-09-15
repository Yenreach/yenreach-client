<?php
    require_once("../includes_public/initialize.php");
    require_once('includes/header.php');

    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);
    
    $explode = !empty($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($explode)){
        $gurl = "fetch_one_blog_post_api.php?string=".$explode;
        $blog = perform_get_curl($gurl);
        if($blog){
            if($blog->status == "success"){         
                $blog = $blog->data;
            } else {
                redirect_to("blogs.php");
            }
        } else {
                die("Blog Link Broken");
            }
    } else {
        die("Wrong Path");
    }
    
    $gurl = "fetch_comments_api.php?string=".$blog->blog_string;
    $comments = perform_get_curl($gurl);
    if($comments){
        if($comments->status == "success"){
            $comments = $comments->data;
        } else {
            $comments = [];
        }
    } else {
        die("Comment Link Broken");
    } 

    

    if(isset($_POST['submit'])){
        if(!$session->is_logged_in()){
            redirect_to("users/auth");   
        }
        $author_string = !empty($session->verify_string) ? (string)$session->verify_string : "";
        $comment = !empty($_POST['comment']) ? (string)$_POST['comment'] : "";
        $blog_string = !empty($explode) ? (string)$explode : "";

        $purl = "add_comment_api.php";
        $pdata = [
                'author_string' => $author_string,
                'comment' => $comment,
                'blog_string' => $blog_string
            ];
        $add_comment = perform_post_curl($purl, $pdata);
        if($add_comment){
            if($add_comment->status == "success"){
                $response = $add_comment->data;
                $session->message("comment has been added successfully");
                $message = "comment has been added successfully";
                header("Refresh:0");
            } else {
                $message = $add_comment->message;     
            }
        } else {
            $message = "Something went wrong";
        }
    } else {
        $author_string = "";
        $comment = "";
        $blog_string = "";
    }

    function formatString($str) {
        $pattern = "/&lt;/i";
        $pattern2 = "/&gt;/i";
        $pattern3 = "/;nbsp;/i";
        $pattern4 = "/&/i";
        $pattern5 = "/amp/i";
        
        $str = preg_replace($pattern, "<", $str);
        $str = preg_replace($pattern2, ">", $str);
        $str = preg_replace($pattern3, " ", $str);
        $str = preg_replace($pattern4, "", $str);
        $str = preg_replace($pattern5, "", $str);
        echo $str;
      };
?>
<html>
<style>
    .img-full {
        width: 100%;
        height: 400px;
    }
    @media only screen and (min-width:850px){
    .img-full {
        width: 80%;
        height: 400px;
    }
    }


</style>
<body>
    <?php include_layout_template("header.php"); ?>
    <main class="col-12 mx-auto" style="margin-top:6rem">
        <div class="container py-5 px-md-5" data-aos="fade-up">
            <div class='mb-4'>
                <h1 class='fw-bolder text-capitalize blog-title mb-3 text-black' style="font-size: 50px"><?php echo $blog->title; ?></h1>
                <div class='d-flex align-items-center' style="gap:1.5rem">
                    <div class="rounded-circle" style="width:40px; height:40px; background: gray"></div>
                    <div class='d-flex flex-column'>
                        <span class='fw-bold'>Author: <?php echo $blog->author; ?></span>
                        <span>posted_at: <?php echo(date("Y-m-d", $blog->created_at)); ?></span>
                    </div>
                </div>
            </div>
            <div class='blog-img rounded-top img-full'>
                <?php if (!empty($blog->file_path)) {  ?>
                    <img src="<?php echo"images/".$blog->file_path.".jpg"; ?>" alt="<?php echo $business->name;  ?>" style="width:100%; height:100%;object-fit: cover;">
                <?php }?>
            </div>
            <div class='my-4 mb-5'>
                <?php
                    echo  htmlspecialchars_decode($blog->post);
                ?>
            </div>
            <form action="" method="post" class="">
                <h3 class="fw-bold">Leave a Reply</h3>
                <textarea name="comment" id="coment" cols="30" rows="10" class="form-control">Your comment here</textarea>
                <button name="submit" type="submit" class="d-block btn btn-success my-3">Post Comment</button>
            </form>
            <div class='rounded d-flex flex-column mt-4' style="gap:2rem;">
                <?php
                    if (!empty($comments)) {
                        foreach($comments as $comment){
                ?>
                        <div class=''>
                            <div class='d-flex align-items-center gap-4 justify-content-between'>
                                <div class='d-flex align-items-center gap-4'>
                                    <div class="rounded-circle" style="width:40px; height:40px; background: gray"></div>
                                    <span class='fw-bold'><?php echo $comment->author; ?></span>
                                </div>
                                <span style='font-size:14px'>posted_at: <?php echo(date("Y-m-d", $comment->created_at)); ?></span>
                            </div>
                            <div><?php echo $comment->comment; ?></div>
                        </div>
                <?php }} ?> 
            </div>
        </div>
    </main>

<script>
    let text =<?php 
    // echo json_encode($message); 
    ?>;
    // console.log(text)
</script>

<?php include_layout_template("footer.php"); ?>
