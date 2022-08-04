<?php
  require_once('../../config/connect.php');
  require_once('includes/session.php');
  require_once('includes/header.php');
  require_once('includes/topbar.php');
  require_once('includes/sidebar.php');

  $query = mysqli_query($link, "SELECT * FROM `businesses`");
  $result = mysqli_fetch_assoc($query);
  $numOfBusiness = mysqli_num_rows($query);
  $bus_break = mysqli_query($link, "SELECT COUNT(id) as counts, subscription_type FROM (SELECT * FROM businesses) as bus GROUP BY subscription_type");

  $query2 = mysqli_query($link, "SELECT * FROM `users`");
  $result2 = mysqli_fetch_assoc($query2);
  $numOfUsers = mysqli_num_rows($query2);
  $user_break = mysqli_query($link, "SELECT COUNT(id) as counts, confirmed_email FROM (SELECT * FROM users) as user GROUP BY confirmed_email");

  $query3 = mysqli_query($link, "SELECT * FROM `messages`");
  $result3 = mysqli_fetch_assoc($query3);
  $numOfNot = mysqli_num_rows($query3);
  $not_break = mysqli_query($link, "SELECT COUNT(id) as counts, status FROM (SELECT * FROM messages) as mess GROUP BY status");

  $query4 = mysqli_query($link, "SELECT * FROM `newsletter_subscribers`");
  $numOfNews = mysqli_num_rows($query4);

  $today = date("Y-m-d", strtotime("today"));
  $query5 = mysqli_query($link, "SELECT * FROM `businesses` WHERE date(datecreated) = '$today'");
  $numOfsig1 = mysqli_num_rows($query5);

  $query6 = mysqli_query($link, "SELECT * FROM `users` WHERE date(datecreated) = '$today'");
  $numOfsig2 = mysqli_num_rows($query6);

  $query7 = mysqli_query($link, "SELECT COUNT(id) as counts, date(datecreated) as dates FROM (SELECT * FROM businesses) as visits GROUP BY date(datecreated)");
  $dates = array();
  $values = array();
  $query8 = mysqli_query($link, "SELECT COUNT(id) as counts, date(datecreated) FROM (SELECT * FROM users) as visits GROUP BY date(datecreated)");
  $dates2 = array();
  $values2 = array();
  $query9 = mysqli_query($link, "SELECT COUNT(id) as counts, date(datecreated) FROM (SELECT * FROM messages) as visits GROUP BY date(datecreated)");
  $dates3 = array();
  $values3 = array();
  $query10 = mysqli_query($link, "SELECT COUNT(id) as counts, date(datecreated) FROM (SELECT * FROM newsletter_subscribers) as visits GROUP BY date(datecreated)");
  $dates4 = array();
  $values4 = array();
   while($result7 = mysqli_fetch_assoc($query7)) {
     $dates[] = $result7['dates'];
     $values[] = $result7['counts'];
    }
  while($result8 = mysqli_fetch_assoc($query8)) {
    $values2[] = $result8['counts'];
  }
  while($result9 = mysqli_fetch_assoc($query9)) {
    $values3[] = $result9['counts'];
   }
   while($result10 = mysqli_fetch_assoc($query10)) {
     $values4[] = $result7['counts'];
    }

    $query11 = mysqli_query($link, "SELECT * FROM `page_visits` WHERE datecreated='$today'");
    $numOfvisits = mysqli_num_rows($query11);
    $visits_break = mysqli_query($link, "SELECT COUNT(business_id) as counts, business_id FROM (SELECT * FROM page_visits WHERE datecreated='$today') as visits GROUP BY business_id");
 ?>

<main id="main" class="main">

<section class="section dashboard">
  <div class="row">

    <!-- Left side columns -->
    <div class="col-lg-12">
      <div class="row">

        <!-- Sales Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card sales-card">

            <div class="card-body">
              <h5 class="card-title">Businesses</h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-shop"></i>
                </div>
                <div class="ps-3">
                  <h6>Total | <?php echo $numOfBusiness; ?></h6>
                  <?php while($res_bus_break = mysqli_fetch_assoc($bus_break)) { ?>
                  <span class="text-success small pt-1 fw-bold"><?php if($res_bus_break['subscription_type']==4){echo "Premium:";} else if($res_bus_break['subscription_type']==3){echo "Silver:";} else if($res_bus_break['subscription_type']==2){echo "Silver:";} else{echo "Free:";} ?></span> <span class="text-muted small pt-2 ps-1"><?php echo $res_bus_break['counts']; ?></span>
                <?php } ?>
                </div>
              </div>
            </div>

          </div>
        </div><!-- End Sales Card -->

        <!-- Revenue Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card revenue-card">

            <div class="card-body">
              <h5 class="card-title">Users </h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-people-fill"></i>
                </div>
                <div class="ps-3">
                  <h6>Total | <?php echo $numOfUsers; ?></h6>
                  <?php while($res_user_break = mysqli_fetch_assoc($user_break)) { ?>
                  <span class="text-success small pt-1 fw-bold"><?php if($res_user_break['confirmed_email']==1){echo "Verified:";} else {echo "Unverified:";} ?></span> <span class="text-muted small pt-2 ps-1"><?php echo $res_user_break['counts']; ?></span>
                <?php } ?>
                </div>
              </div>
            </div>

          </div>
        </div><!-- End Revenue Card -->

        <!-- Customers Card -->
        <div class="col-xxl-4 col-md-6">
          <div class="card info-card revenue-card">

            <div class="card-body">
              <h5 class="card-title">Notifications</h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-envelope"></i>
                </div>
                <div class="ps-3">
                  <h6>Total | <?php echo $numOfNot; ?></h6>
                  <?php while($res_not_break = mysqli_fetch_assoc($not_break)) { ?>
                  <span class="text-success small pt-1 fw-bold"><?php if($res_not_break['status']==1){echo "Treated:";} else {echo "Pending:";} ?></span> <span class="text-muted small pt-2 ps-1"><?php echo $res_not_break['counts']; ?></span>
                <?php } ?>
                </div>
              </div>
            </div>

          </div>
        </div><!-- End Revenue Card -->

        <div class="col-xxl-4 col-md-6">
          <div class="card info-card revenue-card">

            <div class="card-body">
              <h5 class="card-title">NewsLetter Subscribers</h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-newspaper"></i>
                </div>
                <div class="ps-3">
                  <h6>Total | <?php echo $numOfNews; ?></h6>
                </div>
              </div>
            </div>

          </div>
        </div><!-- End Revenue Card -->

        <!-- Reports -->
        <div class="col-12">
          <div class="card">

            <div class="card-body">
              <h5 class="card-title">Reports <span>/Today</span></h5>

              <!-- Line Chart -->
              <div id="reportsChart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new ApexCharts(document.querySelector("#reportsChart"), {
                    series: [{
                      name: 'Business Sign Up',
                      data: [<?php foreach ($values as $value){echo $value.", ";} ?>],
                    }, {
                      name: 'Users Sign Up',
                      data: [<?php foreach ($values2 as $value){echo $value.", ";} ?>]
                    }, {
                      name: 'Notifications',
                      data: [<?php foreach ($values3 as $value){echo $value.", ";} ?>]
                    }, {
                      name: 'Newslettr Sign Ups',
                      data: [<?php foreach ($values4 as $value){echo $value.", ";} ?>]
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
                    colors: ['#4154f1', '#2eca6a', '#ff771d', '#000000'],
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

        <!-- Recent Sales -->
        <div class="col-12">
          <div class="card recent-sales">

            <div class="card-body">
              <h5 class="card-title">User Sign Ups <span>| Today | <?php echo $numOfsig2; ?></span></h5>

              <table class="table table-borderless datatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">User Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone Number</th>
                    <th scope="col">Verification</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($result6 = mysqli_fetch_assoc($query6)){
                    $id2 = $result6['id'];
                    $get_busi = mysqli_query($link, "SELECT * FROM businesses WHERE id='$id2'");
                    $res_get_busi = mysqli_fetch_assoc($get_busi);
                    ?>
                    <tr>
                      <th scope="row"><a href="#">#<?php echo $result6['id']; ?></a></th>
                      <td><?php echo $result6['name']; ?></td>
                      <td class="fw-bold"><a href="mail.php?email=<?php echo $result6['email']; ?>" class="text-primary"><?php echo $result6['email']; ?> <i class="bx bx-envelope"></i></a></td>
                      <td><a href="tel:<?php echo $res_get_busi['phonenumber']; ?>" class="text-primary"><?php echo $res_get_busi['phonenumber']; ?> <i class="bx bx-phone"></i></a></td>
                      <?php if($result6['confirmed_email']==1){ ?>
                      <td><span class="badge bg-success">Verified</span></td>
                    <?php } else{ ?>
                      <td><span class="badge bg-danger">Not Verified</span></td>
                    <?php } ?>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>

            </div>

          </div>
        </div><!-- End Recent Sales -->

        <!-- Top Selling -->
        <div class="col-12">
          <div class="card top-selling">

            <div class="card-body pb-0">
              <h5 class="card-title">Business Sign Ups <span>| Today | <?php echo $numOfsig1; ?></span></h5>

              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Business Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone Number</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($result5 = mysqli_fetch_assoc($query5)){ ?>
                    <tr>
                      <th scope="row"><a href="#">#<?php echo $result5['id']; ?></a></th>
                      <td><a href="#" class="text-primary fw-bold"><?php echo $result5['name']; ?></a></td>
                      <td><?php echo $result5['category']; ?></td>
                      <td class="fw-bold"><a href="mail.php?email=<?php echo $result5['email']; ?>" class="text-primary"><?php echo $result5['email']; ?> <i class="bx bx-envelope"></i></a></td>
                      <td><a href="tel:<?php echo $result5['phonenumber']; ?>" class="text-primary"><?php echo $result5['phonenumber']; ?> <i class="bx bx-phone"></i></a></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>

            </div>

          </div>
        </div><!-- End Top Selling -->

        <div class="col-12">
          <div class="card top-selling">

            <div class="card-body pb-0">
              <h5 class="card-title">Page Visits <span>| Today | <?php echo $numOfvisits; ?></span></h5>

              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Business Name</th>
                    <th scope="col">Visits</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone Number</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($result11 = mysqli_fetch_assoc($visits_break)){
                      $id = $result11['business_id'];
                      $get_bus = mysqli_query($link, "SELECT * FROM businesses WHERE id='$id'");
                      $res_get_bus = mysqli_fetch_assoc($get_bus);
                    ?>
                    <tr>
                      <th scope="row"><a href="#">#<?php echo $res_get_bus['id']; ?></a></th>
                      <td><a href="#" class="text-primary fw-bold"><?php echo $res_get_bus['name']; ?></a></td>
                      <td><?php echo $result11['counts']; ?></td>
                      <td class="fw-bold"><a href="mail.php?email=<?php echo $res_get_bus['email']; ?>" class="text-primary"><?php echo $res_get_bus['email']; ?> <i class="bx bx-envelope"></i></a></td>
                      <td><a href="tel:<?php echo $res_get_bus['phonenumber']; ?>" class="text-primary"><?php echo $res_get_bus['phonenumber']; ?> <i class="bx bx-phone"></i></a></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>

            </div>

          </div>
        </div><!-- End Top Selling -->

      </div>
    </div><!-- End Left side columns -->


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
