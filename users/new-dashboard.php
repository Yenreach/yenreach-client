<?php
    require_once("../../includes_public/initialize.php");
    $gurl = "fetch_user_by_string_api.php?string=".$_SESSION['verify_string'];
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
  <!--<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">-->

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--<link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">-->
  <!--<link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">-->
  <!--<link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">-->
  
  <!--<link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">-->
  <!--<link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">-->
  <!--<link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">-->

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <!--<link href="assets/css/main.css" rel="stylesheet">-->

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.1.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
    <a href="profile" class="logo d-flex align-items-center">
      <img src="../assets/img/logo.png" alt="">
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle " href="#">
          <i class="bi bi-search"></i>
        </a>
      </li><!-- End Search Icon-->

      <li class="nav-item dropdown pe-3">

        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <?php if(empty($user->image)) {?>
           <img src="../assets/img/user.png" alt="Profile" class="rounded-circle">
         <?php } else { ?>
           <img src="<?php echo "../".$user->image; ?>" alt="Profile" class="rounded-circle">
         <?php } ?>
          <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $user->name; ?></span>
        </a><!-- End Profile Iamge Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6></h6>
            <span>User</span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="profile">
              <i class="bi bi-person"></i>
              <span>My Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="password">
              <i class="bi bi-question-circle"></i>
              <span>Security</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="logout">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->

</header><!-- End Header -->

<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">
      
      <li class="nav-item">
        <li class="nav-item">
          <a class="nav-link collapsed" href="dashboard">
          <i class="bi bi-house"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <?php
            if(isset($_SESSION['business_string'])){
        ?>
                <li class="nav-heading">Business</li>
            
                <li class="nav-item">
                  <a class="nav-link collapsed" href="business_profile">
                  <i class="bi bi-person-badge"></i>
                    <span>Business Profile</span>
                  </a>
                </li><!-- End Profile Page Nav -->
                <li class="nav-item">
                  <a class="nav-link collapsed" href="#" onclick="return alert('This Feature is not available right now. It will be made available very soon. So sorry for the inconvenience')">
                  <i class="bi bi-bricks"></i>
                    <span>Facilities</span>
                  </a>
                </li><!-- End Facilities Page Nav -->
                <li class="nav-item">
                  <a class="nav-link collapsed" href="working_hours">
                  <i class="bi bi-clock"></i>
                    <span>Working Hours</span>
                  </a>
                </li><!-- End Facilities Page Nav -->
                <li class="nav-item">
                  <a class="nav-link collapsed" href="#" onclick="return alert('This Feature is not available right now. It will be made available very soon. So sorry for the inconvenience')">
                  <i class="bi bi-house"></i>
                    <span>Branches</span>
                  </a>
                </li><!-- End Facilities Page Nav -->
                <li class="nav-item">
                  <a class="nav-link collapsed" href="#" onclick="return alert('This Feature is not available right now. It will be made available very soon. So sorry for the inconvenience')">
                  <i class="bi bi-collection-fill"></i>
                    <span>SocialMedia/Website</span>
                  </a>
                </li><!-- End Facilities Page Nav -->
                
                <li class="nav-heading">Subscriptions</li>
                <li class="nav-item">
                  <a class="nav-link collapsed" href="subscription_packages">
                  <i class="bi bi-collection"></i>
                    <span>Subscription Packages</span>
                  </a>
                </li><!-- End Profile Page Nav -->
                <li class="nav-item">
                  <a class="nav-link collapsed" href="business_subscriptions">
                  <i class="bi bi-collection"></i>
                    <span>My Subscriptions</span>
                  </a>
                </li><!-- End Profile Page Nav -->
                
                <li class="nav-heading">Media</li>
                
                <li class="nav-item">
                  <a class="nav-link collapsed" href="#" onclick="return alert('This Feature is not available right now. It will be made available very soon. So sorry for the inconvenience')">
                  <i class="bi bi-image"></i>
                    <span>Photos</span>
                  </a>
                </li><!-- End Profile Page Nav -->
                <li class="nav-item">
                  <a class="nav-link collapsed" href="#" onclick="return alert('This Feature is not available right now. It will be made available very soon. So sorry for the inconvenience')">
                  <i class="bi bi-camera-video"></i>
                    <span>Video</span>
                  </a>
                </li><!-- End Profile Page Nav -->
            
                <li class="nav-heading">Stat</li>
            
                <li class="nav-item">
                  <a class="nav-link collapsed" href="#" onclick="return alert('This Feature is not available right now. It will be made available very soon. So sorry for the inconvenience')">
                    <i class="bi bi-question-circle"></i>
                    <span>Business Review</span>
                  </a>
                </li><!-- End Profile Page Nav -->
                <li class="nav-item">
                  <a class="nav-link collapsed" href="#" onclick="return alert('This Feature is not available right now. It will be made available very soon. So sorry for the inconvenience')">
                  <i class="bi bi-flag"></i>
                    <span>Business Report</span>
                  </a>
                </li><!-- End Profile Page Nav -->
        <?php
            }
        ?>

    <li class="nav-heading">User</li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" onclick="return alert('This Feature is not available right now. It will be made available very soon. So sorry for the inconvenience')">
            <i class="bi bi-person"></i>
            <span>Profile</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" onclick="return alert('This Feature is not available right now. It will be made available very soon. So sorry for the inconvenience')">
            <i class="bi bi-key"></i>
            <span>Security</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" onclick="return alert('This Feature is not available right now. It will be made available very soon. So sorry for the inconvenience')">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </li>
   

  </ul>


</aside><!-- End Sidebar-->


</aside><!-- End Sidebar-->




    <main id="main" class="main">
        <div class="row">
            <div class="col-12 card-container d-flex justify-content-between">
                <!--<div class="card col-8 px-2 bg-danger">-->
                <!--    <div class="card-header">-->
                <!--        <h4 class="card-title">Businesses</h4>-->
                <!--    </div>-->
                <!--    <div class="card-body card-block">-->
                <!--        <div class="table-responsive">-->
                           
                <!--                    <table class="table table-responsive-sm">-->
                <!--                        <thead>-->
                <!--                            <tr>-->
                <!--                                <th class='text-muted'>Business Name</th>-->
                <!--                                <th class='text-muted'>Description</th>-->
                <!--                            </tr>-->
                <!--                        </thead>-->
                <!--                        <tbody class='text-muted'>-->
                                            
                <!--                                    <tr>-->
                <!--                                        <td class='h-50 w-50'></td>-->
                <!--                                        <td class='h-50 w-50'></td>-->
                <!--                                    </tr>-->
                <!--                        </tbody>-->
                <!--                    </table>-->
                <!--                    <div class='col-9 d-flex flex-column justify-content-between align-items-center py-2 border-b-1' style='height:8rem'>-->
                <!--                                        <div class='col-12 d-flex align-items-center justify-content-between'>-->
                <!--                                            <h4 style='font-size:18px' class='font-weight-bold'>Business Location:</h4>-->
                <!--                                            <span class='d-block col-4 font-weight-bold'></span>-->
                <!--                                            </div>-->
                <!--                                            <div class='col-12 d-flex align-items-center justify-content-between'>-->
                <!--                                            <h4 style='font-size:18px'>Date Registered:</h4>    -->
                <!--                                            <span class='d-block col-4'></span>-->
                <!--                                            </div>-->
                <!--                                            <div class='col-12 d-flex align-items-center justify-content-between'>-->
                <!--                                            <h4 style='font-size:18px'>Status:</h4>-->
                <!--                                            <div class='d-flex justify-content-start col-4'>-->
                                                        
                                                                
                                                        <!--        <span class="text-danger  rounded">Suspended/Deactivated</span>-->
                                                            
                                                        <!--        <span class="text-warning   rounded ">Incomplete Registration</span>-->
                                                             
                                                        <!--<span class="text-primary  rounded">Pending Approval</span>-->
                                                              
                                                        <!--        <span class="text-success  rounded">Approved</span>-->
                                                        
                <!--                                        </div>-->
                <!--                    </div> -->
                <!--                    </div>-->
                <!--                                 <div class='col-8 d-flex align-items-center justify-content-between'>-->
                <!--                                            <h4 style='font-size:18px'>About us:</h4>      -->
                <!--                            <div class='d-flex justify-content-center col-4'>-->
                <!--                                <a href="load_business?" class="btn btn-success text-white py-2 px-3 rounded">details</a>-->
                <!--                                </div>-->
                                           
                <!--                            </div>-->
                            
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div class="card-footer">-->
                <!--        <a href="add_new_business" class="btn btn-primary btn-lg">Add Business</a>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
            
        </div>
    </main>  

<footer id="footer" class="footer">
     <div class="copyright">
       &copy; 2021 Copyright <strong><span>yenreach.com</span></strong>. All Rights Reserved
     </div>
   </footer><!-- End Footer -->
   
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>
<script src="assets/js/extra_script.js"></script>

<!-- Vendor JS Files -->
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
<!--<script src="../assets/vendor/php-email-form/validate.js"></script>-->
<!--<script src="../assets/vendor/quill/quill.min.js"></script>-->
<!--<script src="../assets/vendor/tinymce/tinymce.min.js"></script>-->
<!--<script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>-->
<!--<script src="../assets/vendor/chart.js/chart.min.js"></script>-->
<!--<script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>-->
<!--<script src="../assets/vendor/echarts/echarts.min.js"></script>-->
<!--<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>-->


<script>
const businessData = [
    {
    businessName: "ProperJs tech",
    description:'At Offshore And Coastal Link Services Nig. enterprise, we provide Link security services, Purveyor services boat (Local and continental), Supply',
    businessLocation: 'Lagos Nigeria',
    dateRegistered: '20,december,2020',
    status:'declined',
    // aboutUs:'At Offshore And Coastal Link Services Nig. enterprise, we provide Link security services, Purveyor services boat (Local and continental), Supply'
},
    {
    businessName: "Newt tech",
    description:'At Offshore And Coastal Link Services Nig. enterprise, we provide Link security services, Purveyor services boat (Local and continental), Supply',
    businessLocation: 'OYO Nigeria',
    dateRegistered: '20,december,2020',
    status:'Pending',
    // aboutUs:'At Offshore And Coastal Link Services Nig. enterprise, we provide Link security services, Purveyor services boat (Local and continental), Supply'
},
]
const businessList = businessData.map(data => {
    return `<div class="card col-12 col-md-8 col-lg-6 px-2 ms-2 ">
                    <div class="card-header">
                        <h4 class="card-title">Businesses</h4>
                    </div>
                    <div class="card-body card-block">
                        <div class="table-responsive">
                           
                                    <table class="table table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th class='text-muted'>Business Name</th>
                                                <th class='text-muted'>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class='text-muted'>
                                            
                                                    <tr>
                                                        <td class='h-50 w-50'>${data.businessName}</td>
                                                        <td class='h-50 w-50'>${data.description}</td>
                                                    </tr>
                                        </tbody>
                                    </table>
                                    <div class='col-10 d-flex flex-column justify-content-between align-items-center py-2 border-b-1' style='height:8rem'>
                                                        <div class='col-12 d-flex align-items-center justify-content-between'>
                                                            <h4 style='font-size:16px' class='font-weight-bold'>Business Location:</h4>
                                                            <span class='d-block col-4 font-weight-bold'>${data.businessLocation}</span>
                                                            </div>
                                                            <div class='col-12 d-flex align-items-center justify-content-between'>
                                                            <h4 style='font-size:16px'>Date Registered:</h4>    
                                                            <span class='d-block col-4'>${data.dateRegistered}</span>
                                                            </div>
                                                            <div class='col-12 d-flex align-items-center justify-content-between'>
                                                            <h4 style='font-size:16px'>Status:</h4>
                                                            <div class='d-flex justify-content-start col-4'>
                                                              <span class="text-danger rounded">${data.status}</span>
                                                        
                                                                
                                                     <!--        <span class="text-danger  rounded">Suspended/Deactivated</span>-->
                                                            
                                                        <!--        <span class="text-warning   rounded ">Incomplete Registration</span>-->
                                                             
                                                        <!--<span class="text-primary  rounded">Pending Approval</span>-->
                                                              
                                                        <!--        <span class="text-success  rounded">Approved</span>-->
                                                        
                                                        </div>
                                    </div> 
                                    </div>
                                                 <div class='col-9 d-flex align-items-center justify-content-between'>
                                                            <h4 style='font-size:16px'>About us:</h4> 
                                            <div class='d-flex justify-content-center col-4'>
                                                <a href="load_business?" class="btn btn-success text-white py-2 px-3 rounded">details</a>
                                                </div>
                                           
                                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="add_new_business" class="btn btn-primary btn-lg">Add Business</a>
                    </div>
                </div>`;
});
document.querySelector('.card-container').innerHTML = businessList.join('');
</script>
</body>

</html>