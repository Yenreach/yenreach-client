<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Yenreach||Admin Dashboard</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/logo.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">



</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="https://admin.yenreach.com/dashboard" class="brand-logo">
                <img class="logo-abbr" src="../images/logo_1.png" alt="">
                <img class="logo-compact" src="../images/logo_1.png" alt="">
                <!--<img class="brand-title" src="./images/logo-text.png" alt="">-->
                YENREACH
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <!--<div class="search_bar dropdown">
                                <span class="search_icon p-3 c-pointer" data-toggle="dropdown">
                                    <i class="mdi mdi-magnify"></i>
                                </span>
                                <div class="dropdown-menu p-0 m-0">
                                    <form>
                                        <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                                    </form>
                                </div>
                            </div>-->
                        </div>

                        <ul class="navbar-nav header-right">
                            <!--<li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <i class="mdi mdi-bell"></i>
                                    <div class="pulse-css"></div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="list-unstyled">
                                        <li class="media dropdown-item">
                                            <span class="success"><i class="ti-user"></i></span>
                                            <div class="media-body">
                                                <a href="#">
                                                    <p><strong>Martin</strong> has added a <strong>customer</strong> Successfully
                                                    </p>
                                                </a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                        <li class="media dropdown-item">
                                            <span class="primary"><i class="ti-shopping-cart"></i></span>
                                            <div class="media-body">
                                                <a href="#">
                                                    <p><strong>Jennifer</strong> purchased Light Dashboard 2.0.</p>
                                                </a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                        <li class="media dropdown-item">
                                            <span class="danger"><i class="ti-bookmark"></i></span>
                                            <div class="media-body">
                                                <a href="#">
                                                    <p><strong>Robin</strong> marked a <strong>ticket</strong> as unsolved.
                                                    </p>
                                                </a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                        <li class="media dropdown-item">
                                            <span class="primary"><i class="ti-heart"></i></span>
                                            <div class="media-body">
                                                <a href="#">
                                                    <p><strong>David</strong> purchased Light Dashboard 1.0.</p>
                                                </a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                        <li class="media dropdown-item">
                                            <span class="success"><i class="ti-image"></i></span>
                                            <div class="media-body">
                                                <a href="#">
                                                    <p><strong> James.</strong> has added a<strong>customer</strong> Successfully
                                                    </p>
                                                </a>
                                            </div>
                                            <span class="notify-time">3:20 am</span>
                                        </li>
                                    </ul>
                                    <a class="all-notification" href="#">See all notifications <i
                                            class="ti-arrow-right"></i></a>
                                </div>
                            </li>-->
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <i class="mdi mdi-account"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="security.php" class="dropdown-item">
                                        <i class="icon-key"></i>
                                        <span class="ml-2">Security </span>
                                    </a>
                                    <a href="logout.php" class="dropdown-item">
                                        <i class="icon-power"></i>
                                        <span class="ml-2">Logout </span>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="quixnav">
            <div class="quixnav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label first">Dashboard</li>
                    <li><a class="" href="https://admin.yenreach.com/dashboard" aria-expanded="false"><i class="fa fa-home"></i><span class="nav-text">Home</span></a></li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="fa fa-cogs"></i><span class="nav-text">Components</span></a>
                        <ul aria-expanded="false">
                            <li><a href="admins">Admins</a></li>
                            <li><a href="officialmails">Official Mails</a></li>
                            <?php
                                if($_SESSION['autho_level'] <= 2){
                            ?>
                                    <li><a href="send_bulk_mail">Send Bulk Mail</a></li>
                            <?php
                                }
                            ?>
                            <li><a href="business_subscriptions">Business Subscriptions</a></li>
                            <li><a href="business_facilities">Business Facilities</a></li>
                            <li><a href="advert_payment_types">Advert Payment Types</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="fa fa-building"></i>
                    <span class="nav-text">Businesses</span></a>
                        <ul aria-expanded="false">
                            <li><a href="all_businesses">All Businesses</a></li>
                            <li><a href="pending_businesses">Pending Businesses</a></li>
                        </ul>
                    </li>
                    <li><a class="" href="users" aria-expanded="false"><i class="fa fa-users"></i><span class="nav-text">Users</span></a></li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="fa fa-sign-hanging"></i>
                    <span class="nav-text">Billboard Applications</span></a>
                        <ul aria-expanded="false">
                            <li><a href="billboard_applications_pending">Pending Applications</a></li>
                            <li><a href="billboard_applications">All Applications</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="fa fa-folder"></i>
                    <span class="nav-text">Sections/Categories</span></a>
                        <ul aria-expanded="false">
                            <li><a href="sections">Sections</a></li>
                            <li><a href="categories">Categories</a></li>
                        </ul>
                    </li>
                    <li><a href="logout.php"><i class="fa fa-power-off"></i><span class="nav-text">Logout</span></a></li>
                </ul>
            </div>


        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->