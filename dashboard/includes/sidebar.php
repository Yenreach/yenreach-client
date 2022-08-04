<?php $user_id = $_SESSION['tid']; ?>
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link " href="profile.php?tid=<?php echo $id; ?>">
        <i class="bi bi-person"></i>
        <span>Business Profile</span>
      </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="reviews.php?tid=<?php echo $id; ?>">
        <i class="bi bi-envelope"></i>
        <span>Business Reviews</span>
      </a>
    </li><!-- End Contact Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="report.php?tid=<?php echo $id; ?>">
        <i class="bi bi-bar-chart"></i><span>Business Report</span>
      </a>
    </li><!-- End Charts Nav -->

    <li class="nav-heading">Others</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="faq.php?tid=<?php echo $id; ?>">
        <i class="bi bi-question-circle"></i>
        <span>F.A.Q</span>
      </a>
    </li><!-- End F.A.Q Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="logout.php?tid=<?php echo $id; ?>">
        <i class="bi bi-house"></i>
        <span>Home Page</span>
      </a>
    </li><!-- End Home Page Page Nav -->


  </ul>

</aside><!-- End Sidebar-->
