<?php
    require_once("../../includes_public/initialize.php");
    if(!$session->is_logged_in()){
        redirect_to("logout");
    }
    if($session->user_type != "user"){
        redirect_to("logout");
    }
    
    $gurl = "fetch_saved_businesses_api.php?string=".$session->verify_string;
    $businesses = perform_get_curl($gurl);
    if($businesses){
        if($businesses->status == "success"){
            $page = !empty($exploded[1]) ? (int)$exploded[1] : 1;
            $per_page = 40;
            $total_count = count($businesses->data);
            
            $pagination = new Pagination($page, $per_page, $total_count);
            $buses = array_slice($businesses->data, $pagination->offset(), $per_page);
        }
    } else {
        die("Businesses Link Broken");
    }
    
    include_portal_template("header.php");
?>

    <style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap');
            *{
              margin: 0;
              padding: 0;
              box-sizing: border-box;
  font-family: 'Open Sans', sans-serif;
            }
           
            /*.card-content{
              display: flex ;
              justify-content: center;
              align-items: center;
              flex-wrap: wrap;
            }*/
            body{
                overflow-x:hidden;
            }
            
            .card{
              background: #fff;
              height: 32.5rem;
              margin: 5px;
              box-shadow: 0 5px 25px rgb(1 1 1 / 20%);
              border-radius: 5px;
              overflow: hidden;
            }
            
            .card-image{
              background-color: none;
              width: 100%;
              height:15rem;
             /*padding-top: 100%  1:1 Aspect Ratio */
              /* If you want text inside of it */
            }
            
            .card-img {
            height:100%;
            width:100%;
            background-size:cover;
              background-repeat: no-repeat;
              background-position: center center;
              
            }
            
            .card-image img{
              max-width: 100%;
              height: 15rem;
              /*background-color:red;*/
            }
            .info-container{
            height:17rem;
            display:flex;
            flex-direction:column;
            justify-content:space-evenly;
            align-items:flex-start;

            }
            
            .card-info{
              height: 9rem;
              padding: 0 10px;
              width:100%;
            }
            
            .card-info h3{
              color: #083640;
              font-size: 16px;
              font-weight: bold;
              text-align: left;
               font-family: 'Open Sans', sans-serif;
              padding:5px 0;
            }
            
            .card-info p{
                font-family: 'Open Sans', sans-serif;
              font-size: 14px;
              text-align:left;
              color:#222;
              margin-right:20px;
              /*padding-bottom:5px;*/
            }
            
            .pagination{
            width:100%;
              text-align: center;
              margin: 30px 0 60px;
              user-select: none;
            }
            
            .pagination li{
              display: inline-block;
              margin: 3px;
              box-shadow: 0 5px 25px rgb(1 1 1 / 10%);
            }
            
            .pagination li a{
              color: #00c853;
              text-decoration: none;
              font-size: 1rem;
            }
            
            .previous-page, .next-page{
              background: #00c853!important;
              width: 80px;
              border-radius: 45px;
              cursor: pointer;
              transition: 0.3s ease;
            }
            
            .previous-page:hover{
              transform: translateX(-5px);
            }
            
            .next-page:hover{
              transform: translateX(5px);
            }
            
            .current-page, .dots{
              background: #00c853;
              width: 45px;
              border-radius: 50%;
              cursor: pointer;
            }
            
            .pagination-active{
              background-color: #00c853!important;
              color:#00c853;
            }
            
            .disable{
              background: #ccc;
            }
      
        </style>
    <main id="main" class="main">
        <div class="row">
            <div class="container">
                <!-- ======= Breadcrumbs ======= -->
                <section class="breadcrumbs">
                    <div class="container">
                        <ol>
                            <li><a href="dashboard">Dashboard</a></li>
                            <li>Favourites</li>
                        </ol>
                        <h2>Favourite Businesses</h2>
                        <p>Businesses that you still want to check out later</p>
                        <?php echo output_message($message); ?>
                    </div>
                </section><!-- End Breadcrumbs -->
            </div>
        </div>
        <div class="container">
            <?php
                if($businesses->status == "success"){
            ?>
            
            <?php
                } else {
                    echo '<p class="text-light">'.$businesses->message.'</p>';
                }
            ?>
            <div class="row" style="padding-top: 30px;">
                <div class="card-content d-flex flex-wrap flex-lg-wrap justify-content-start align-items-start ">
                    <?php
                        foreach($buses as $business){
                    ?>
                        <div class="col-12 col-lg-3 col-md-4" >
                            <div class="card">
                                <div class="card-image" loading="lazy">
                                    <div class="card-img" style="background-image: url(<?php
                                        if(!empty($business->photos)){
                                            $photo = array_shift($business->photos);
                                            if(file_exists("../".$photo->filepath)){
                                                echo "../".$photo->filepath;
                                            } else {
                                                if(!empty($business->filename)){
                                                    if(file_exists("../images/thumbnails/".$business->filename.".jpg")){
                                                        echo "../images/thumbnails/".$business->filename.".jpg";
                                                    } else {
                                                        if(file_exists("../images/".$business->filename.".jpg")){
                                                            echo "../images/".$business->filename.".jpg";
                                                        } else {
                                                            echo "../assets/img/office_building.png";
                                                        }
                                                    }
                                                } else {
                                                    echo "../assets/img/office_building.png";
                                                }
                                            }
                                        } else {
                                            if(!empty($business->filename)){
                                                if(file_exists("../images/thumbnails/".$business->filename.".jpg")){
                                                    echo "../images/thumbnails/".$business->filename.".jpg";
                                                } else {
                                                    if(file_exists("../images/".$business->filename.".jpg")){
                                                        echo "../images/".$business->filename.".jpg";
                                                    } else {
                                                        echo "../assets/img/office_building.png";
                                                    }
                                                }
                                            } else {
                                                echo "../assets/img/office_building.png";
                                            }
                                        }
                                    ?>)">&nbsp;
                                    </div>
                                
                                    
                                </div>
                                <div class='info-container'>
                                    <div class="card-info">
                                        <h3><?php echo $business->name; ?></h3>
                                        <p>
                                            <?php
                                                $desc_array = array();
                                                $explode_desc = explode(' ', $business->description);
                                                $explode_count = count($explode_desc);
                                                if($explode_count >= 15){
                                                    for($i=0; $i<=15; $i++){
                                                        $desc_array[] = $explode_desc[$i];
                                                    }
                                                } else {
                                                    foreach($explode_desc as $desc){
                                                        $desc_array[] = $desc;
                                                    }
                                                }
                                                echo join(' ', $desc_array)." ...";
                                                $name_array = explode(' ', $business->name);
                                                $name = join('_', $name_array);
                                            ?>
                                        </p>
                                    </div>
                                    <div class=' mx-2'>
                                        
                                        <?php
                                            if(!empty($business->categories)){
                                                echo '<p>';
                                                foreach($business->categories as $category){
                                                    echo ' <a href="../category?category='.urlencode($category->category).'" style="margin-top: 1px; font-size:12px;color:#00C853">#'.$category->category.'</a> ';
                                                }
                                                echo '</p>';
                                            }
                                        ?>
    
                                    </div>
                                    <div class="ms-2 w-100">
                                        <a href="../business?<?php echo $business->verify_string; ?>/<?php echo $business->state ?>/<?php echo $business->town ?>/<?php echo $name.".html"; ?>"
                                        class="btn btn-success">View Business</a>
                                    </div>
                                    <div class="ms-2  w-100">
                                        <a href="remove_saved_business?<?php echo $business->verify_string; ?>" class="btn btn-danger"
                                        onclick="return confirm('Do you really want to remove this Business from Saved Businesses?')">Remove Business</a>
                                    </div>
                                </div>
                            </div>
                        </div>      
                    <?php
                        }
                    ?>    
                </div>
                <?php
                    if($pagination->total_pages() > 1){
                ?>
                        <div class="pagination mx-lg-auto">
                            <?php
                                if($pagination->has_previous_page()){
                            ?>
                                    <li class="page-item previous-page"><a class="page-link text-dark" href="saved_businesses?<?php echo $pagination->previous_page(); ?>">Prev</a></li>
                            <?php
                                }
                            ?>
                            <?php
                                for($i=1; $i<=$pagination->total_pages(); $i++){
                            ?>
                                    <li class="page-item <?php if($page == $i){ echo "pagination-active current-page"; } ?>"><a class="page-link <?php if($page != $i){ echo "text-dark"; } ?>" href="saved_businesses?<?php echo $i; ?>"><?php echo $i; ?></a></li>
                            <?php
                                }
                            ?>
                            <?php
                                if($pagination->has_next_page()){
                            ?>
                                    <li class="page-item next-page"><a class="page-link text-dark" href="saved_businesses?<?php echo $pagination->next_page(); ?>">Next</a></li>
                            <?php
                                }
                            ?>
                        </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </main>

<?php include_portal_template("footer.php"); ?>