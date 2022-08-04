<?php
    require_once("../../includes_public/initialize.php");
    $url = $_SERVER['REQUEST_URI'];
    $exploded = explode("?", $url);

    $coded_string = isset($exploded[1]) ? (string)$exploded[1] : "";
    if(!empty($coded_string)){
        $string = base64_decode($coded_string);
        $gurl = "fetch_user_by_string_api.php?string=".$string;
        $users = perform_get_curl($gurl);
        if($users){
            if($users->status == "success"){
                $user = $users->data;
                
            } else {
                die($users->message);
            }
        } else {
            die("User Link Broken");
        }
    } else {
        die("Wrong Path");
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
      <meta charset="utf-8">
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
      <title>Yenreach || Dashboard</title>
      <meta content="" name="description">
      <meta content="" name="keywords">
    
      <!-- Favicons -->
      <link href="../assets/img/favicon.png" rel="icon">
      <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    
      <!-- Google Fonts -->
      <link href="https://fonts.gstatic.com" rel="preconnect">
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    
      <!-- Vendor CSS Files -->
      <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
      <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
      <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
      <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
      <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
      <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
    
      <!-- Template Main CSS File -->
      <link href="assets/css/style.css" rel="stylesheet">
    
      <!-- =======================================================
      * Template Name: NiceAdmin - v2.1.0
      * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
      * Author: BootstrapMade.com
      * License: https://bootstrapmade.com/license/
      ======================================================== -->
    </head>
    <body>
        <main>
            <div class="container">
                <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                                
                
                                <div class="card mb-3">
                                    <div class="d-flex justify-content-center">
                                            <a href="../index.php" class="logo d-flex align-items-center w-auto">
                                                <img src="../assets/img/logo.png" alt="" height="200px" width="auto">
                                            </a>
                                        </div><!-- End Logo -->
                                    <div class="card-header">
                                        <div class="pb-2">
                                            <h5 class="card-title text-center pb-0 fs-4">Create your Account</h5>
                                            <?php echo output_message($message); ?>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p>
                                            Your Password reset link has been sent to your mail - <b><?php echo $user->email; ?></b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>    
                    </div>
                </section>
            </div>
        </main>
    
        <!-- Vendor JS Files -->
        <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
        <script src="../assets/vendor/php-email-form/validate.js"></script>
        <script src="../assets/vendor/quill/quill.min.js"></script>
        <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
        <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
        <script src="../assets/vendor/chart.js/chart.min.js"></script>
        <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
        <script src="../assets/vendor/echarts/echarts.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        
        <!-- Template Main JS File -->
        <script src="assets/js/main.js"></script>
        <script src="assets/js/extra_script.js"></script>
    </body>
</html>