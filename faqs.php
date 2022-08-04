<?php
  require_once('config/connect.php');
  require_once('includes/header.php');
?>

  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <section class="breadcrumbs">
      <div class="container">

        <ol>
          <li><a href="index.php">Home</a></li>
          <li>FAQs</li>
        </ol>
        <h2>Frequently Asked Questions</h2>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Frequently Asked Questions Section ======= -->
    <section id="faq" class="faq">
      <div class="container" data-aos="fade-up">

        <ul class="faq-list accordion" data-aos="fade-up">
          <?php
            $count = 1;
            $faq_query = mysqli_query($link, "SELECT * FROM `faqs`");
            while($faq_result = mysqli_fetch_assoc($faq_query)) {
              $question = $faq_result['question'];
              $answer = $faq_result['answer'];
           ?>
          <li>
            <a data-bs-toggle="collapse" class="collapsed" data-bs-target="#faq<?php echo $count?>"><?php echo $question ?><i class="bx bx-chevron-down icon-show"></i><i class="bx bx-x icon-close"></i></a>
            <div id="faq<?php echo $count?>" class="collapse" data-bs-parent=".faq-list">
              <p>
                <?php echo $answer ?>
              </p>
            </div>
          </li>
          <?php $count++; } ?>

        </ul>

      </div>
    </section><!-- End Frequently Asked Questions Section -->

  </main><!-- End #main -->

  <?php
    require_once('includes/footer.php');
  ?>
