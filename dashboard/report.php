<?php
  require_once('../config/connect.php');
  require_once('../config/session.php');
  require_once('includes/header.php');
  require_once('includes/topbar.php');
  require_once('includes/sidebar.php');

  $id = $_SESSION['tid'];
  $query = mysqli_query($link, "SELECT * FROM `users` WHERE `id`='$id'");
  $result = mysqli_fetch_assoc($query);

  if(isset($_GET['bus'])){
    $bus = $_GET['bus'];

    $business_query = mysqli_query($link, "SELECT * FROM `page_visits` WHERE `business_id`='$bus'");
    $numberOfRows = mysqli_num_rows($business_query);

    $reviews_query = mysqli_query($link, "SELECT * FROM `reviews` WHERE `business`='$bus'");
    $numberOfRows2 = mysqli_num_rows($reviews_query);
  }
 ?>

 <main id="main" class="main">

   <section class="section dashboard">
     <div class="row">

       <!-- Left side columns -->
       <div class="col-lg-8 order-2">
         <div class="row">

           <!-- Revenue Card -->
           <div class="col-xxl-6 col-md-6">
             <div class="card info-card revenue-card">

               <div class="card-body">
                 <h5 class="card-title">Business Reviews <span>| Total</span></h5>

                 <div class="d-flex align-items-center">
                   <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                     <i class="bi bi-envelope"></i>
                   </div>
                   <div class="ps-3">
                     <h6><?php if(isset($_GET['bus'])){echo $numberOfRows2;}else {} ?></h6>
                   </div>
                 </div>
               </div>

             </div>
           </div><!-- End Revenue Card -->

           <!-- Customers Card -->
           <div class="col-xxl-6 col-xl-6">

             <div class="card info-card customers-card">

               <div class="card-body">
                 <h5 class="card-title">Visitors on your Business Page <span>| Total</span></h5>

                 <div class="d-flex align-items-center">
                   <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                     <i class="bi bi-people"></i>
                   </div>
                   <div class="ps-3">
                     <h6><?php if(isset($_GET['bus'])){echo $numberOfRows;}else {} ?></h6>
                   </div>
                 </div>

               </div>
             </div>

           </div><!-- End Customers Card -->

           <?php if(isset($_GET['bus'])) { ?>
           <!-- Reports -->
           <div class="col-12">
             <div class="card">

               <div class="card-body">
                 <h5 class="card-title">Business Reports <span>/Chart</span></h5>

                 <!-- Line Chart -->
                 <div id="reportsChart"></div>
                 <?php
                 $query2 = mysqli_query($link, "SELECT COUNT(business_id) as counts, datecreated FROM (SELECT * FROM page_visits WHERE business_id='$bus') as visits GROUP BY datecreated");
                 $dates = array();
                 $values = array();
                 $query3 = mysqli_query($link, "SELECT COUNT(business) as counts, datecreated FROM (SELECT * FROM reviews WHERE business='$bus') as visits GROUP BY datecreated");
                 $dates2 = array();
                 $values2 = array();
                  while($result2 = mysqli_fetch_assoc($query2)) {
                  $dates[] = $result2['datecreated'];
                  $values[] = $result2['counts'];
               }
               while($result3 = mysqli_fetch_assoc($query3)) {
               $dates2[] = $result3['datecreated'];
               $values2[] = $result3['counts'];
            }
                  ?>

                 <script>
                   document.addEventListener("DOMContentLoaded", () => {
                     new ApexCharts(document.querySelector("#reportsChart"), {
                       series: [{
                         name: 'Reviews',
                         data: [<?php foreach ($values2 as $value){echo $value.", ";} ?>],
                       }, {
                         name: 'Visitors',
                         data: [<?php foreach ($values as $value){echo $value.", ";} ?>]
                       }],
                       chart: {
                         height: 350,
                         type: 'area',
                         toolbar: {
                           show: false
                         },
                       },
                       markers: {
                         size: 4
                       },
                       colors: ['#4154f1', '#2eca6a'],
                       fill: {
                         type: "gradient",
                         gradient: {
                           shadeIntensity: 1,
                           opacityFrom: 0.3,
                           opacityTo: 0.4,
                           stops: [0, 90, 100]
                         }
                       },
                       dataLabels: {
                         enabled: false
                       },
                       stroke: {
                         curve: 'smooth',
                         width: 2
                       },
                       xaxis: {
                         type: 'datetime',
                         categories: [<?php foreach ($dates as $date) {echo '"'.$date.'", ';} ?> ]
                       },
                       tooltip: {
                         x: {
                           format: 'dd/MM/yy'
                         },
                       }
                     }).render();
                   });
                 </script>
                 <!-- End Line Chart -->

               </div>

             </div>
           </div><!-- End Reports -->
         <?php } ?>

         </div>
       </div><!-- End Left side columns -->

       <!-- Right side columns -->
       <div class="col-lg-4 order-1">
         <div class="card">
           <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

             <h2>Select Business</h2>
             <form role="form" method="POST" enctype="multipart/form-data">
               <select class="form-select my-3" name="select_bus" id="select_bus" style="width: 100%;" aria-label="Floating label select example">
                 <option value="choose" selected>Choose</option>
                 <?php
                 $bus_query = mysqli_query($link, "SELECT `id`, `name` FROM `businesses` WHERE `user`='$id'");
                 while($bus_result = mysqli_fetch_assoc($bus_query)) { ?>
                   <option value="<?php echo $bus_result['id']; ?>" <?php if(isset($_GET['bus']) && $_GET['bus']==strtolower($bus_result['id'])){echo "selected";} ?>><?php echo strtoupper($bus_result['name']) ?></option>
                 <?php } ?>
               </select>
               <button class="btn btn-primary w-100" id="get_info" type="submit" name="get_info">Load Info</button>
              </form>
              <?php if(isset($_POST['get_info'])){
                $bus = $_POST['select_bus'];
                if($bus=="choose"){
                  echo "<script>window.location='report.php?tid=$id';</script>";
                }else {
                  echo "<script>window.location='report.php?tid=$id&bus=".$bus."';</script>";
                }
              }
              ?>
           </div>
         </div>
       </div><!-- End Right side columns -->

     </div>
   </section>

 </main><!-- End #main -->
 <!-- ======= Footer ======= -->
 <footer id="footer" class="footer">
   <div class="copyright">
     &copy; 2021 Copyright <strong><span>yenreach.com</span></strong>. All Rights Reserved
   </div>
 </footer><!-- End Footer -->

 <?php
 require_once('includes/footer.php');
 ?>
