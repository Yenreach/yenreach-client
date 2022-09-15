<?php
    require_once("../includes_public/initialize.php");


    $gurl = "fetch_terms_api.php";
    $terms = perform_get_curl($gurl);
    if($terms){
        if($terms->status == "success"){         
            $terms = $terms->data;
        } else {
            redirect_to("blogs.php");
        }
    } else {
            die("Blog Link Broken");
        }


    
?>
<html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Yenreach Terms of Service</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- <link href="./css/style.css" rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@1,300;1,600&display=swap" rel="stylesheet">

    <style>
        html{
            font-family: 'Open Sans', sans-serif;
                }
                body{
                   max-width:100vw;
                   color: #000;
                }
        .container {
  width: 100%;
  padding-right: 15px;
  padding-left: 15px;
  margin-right: auto;
  margin-left: auto; }
  @media (min-width: 576px) {
    .container {
      max-width: 540px; } }
  @media (min-width: 768px) {
    .container {
      max-width: 720px; } }
  @media (min-width: 992px) {
    .container {
      max-width: 960px; } }
  @media (min-width: 1200px) {
    .container {
      max-width: 1140px; } }

.container-fluid {

  width: 100%;
  padding-right: 15px;
  padding-left: 15px;
  margin-right: auto;
  margin-left: auto; }

.row {
  display: flex;
  flex-wrap: wrap;
  margin-right: -15px;
  margin-left: -15px;
 }
.photo-content {
  position: relative; }
  .photo-content .cover-photo {
    background: url(../images/profile/cover.jpg);
    background-size: cover;
    background-position: center;
    min-height: 250px;
    width: 100%; }
  .photo-content .profile-photo {
    bottom: -75px;
    left: 100px;
    max-width: 150px;
    position: absolute; 
  }
p span{
   color: #454545

}
p{
    font-weight: 400;
    font-size: 16px;
    line-height: 25px;
    margin-top: 0;
  margin-bottom: 1rem;
  text-align: justify;
text-justify: inter-word;
}
ol{
    display:inline-block;
      font-weight: 400;
    font-size: 16px;
    line-height: 25px;
    margin-top: 0;
  margin-bottom: 1rem;
  text-align: left;
text-justify: inter-word;
}
strong{
    font-size: 17px;
    font-family: 'Open Sans', sans-serif;
}
h4{
    font-size: 1;
}
.bold-text{
    font-weight: 600;
}
.shadow-sm {
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important; }

.shadow {
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important; }

.shadow-lg {
  box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important; }

.shadow-none {
  box-shadow: none !important; }
  
h1, h2, h3, h4, h5, h6 {
  margin-top: 0;
  margin-bottom: 0.5rem;
   text-align: justify;
text-justify: inter-word;}
.col-12{
    padding: 0 15px;
}

 h4{
     font-size: 20px;
     line-height: 27px;
 } 
 .cover-photo {
     width:50vw;
     margin:0 auto;
     height:15rem;
 }
    </style>

</head>
    <body>
        
        <div class="col-12 ">
            <div class="container">
                <?php
                    echo  htmlspecialchars_decode($terms->content);
                ?>
                <div class="d-flex justify-content-start py-3 align-items-center w-50">
                    <button class="btn bg-success py-2 px-4 text-white" style="font-size: 16px;">I Decline</button>
                    <button class="btn bg-success py-2 px-4 text-white ml-3" style="font-size: 16px;">I Agree</button>
                </div>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</html>
