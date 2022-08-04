<?php $user_id = $_SESSION['tid']; ?>
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link " href="dashboard.php?tid=<?php echo $id; ?>">
        <i class="bi bi-bar-chart"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="businesses.php?tid=<?php echo $id; ?>">
        <i class="bi bi-shop"></i>
        <span>Businesses</span>
      </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="users.php?tid=<?php echo $id; ?>">
        <i class="bi bi-person"></i>
        <span>Users</span>
      </a>
    </li><!-- End Contact Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="subscriptions.php?tid=<?php echo $id; ?>">
        <i class="bi bi-arrow-down-square"></i><span>Subscriptions</span>
      </a>
    </li><!-- End Charts Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="notifications.php?tid=<?php echo $id; ?>">
        <i class="bi bi-bell"></i><span>Notifications</span>
      </a>
    </li><!-- End Charts Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="categories.php?tid=<?php echo $id; ?>">
        <i class="bi bi-tags"></i><span>Categories</span>
      </a>
    </li><!-- End Charts Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="images.php?tid=<?php echo $id; ?>">
        <i class="bi bi-card-image"></i><span>Images</span>
      </a>
    </li><!-- End Charts Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="mail.php?tid=<?php echo $id; ?>">
        <i class="bi bi-envelope-dash"></i><span>Send Mail</span>
      </a>
    </li><!-- End Charts Nav -->

    <li class="nav-heading">Others</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="faqs.php?tid=<?php echo $id; ?>">
        <i class="bi bi-question-circle"></i>
        <span>F.A.Q</span>
      </a>
    </li><!-- End F.A.Q Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="blogs.php?tid=<?php echo $id; ?>">
        <i class="bi bi-newspaper"></i>
        <span>Blog Post</span>
      </a>
    </li><!-- End F.A.Q Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="../logout.php?tid=<?php echo $id; ?>">
        <i class="bi bi-house"></i>
        <span>Home Page</span>
      </a>
    </li><!-- End Home Page Page Nav -->


  </ul>

</aside><!-- End Sidebar-->
