<?php
  require_once('includes/header.php');
  require_once('config/connect.php');

  if(isset($_GET['sub'])){
    $sub = $_GET['sub'];
    $tid = $_GET['tid'];

    if($sub == "premium"){
      $price = 4999.99;
    }else if($sub=="gold"){
      $price = 3499.99;
    }else{
      $price = 1499.99;
    }
  }
?>

  <main id="main">

    <section class="inner-page" style="padding-bottom: 10px; margin-bottom: 10px">
      <div class="container">
        <div class="py-5 text-center">
      <img class="d-block mx-auto mb-4" src="assets/img/logo.png" alt="" width="200" height="100">
      <h2 style="color: #083640">SUBSCRIPTION FORM</h2>
      <p class="lead">SUBSCRIPTION PACKAGE: <SPAN style="color: #00C853;"><?php echo $sub ?></SPAN></p>
    </div>

    <div class="row g-5">
      <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">Payment Information</h4>
        <form id="paymentForm">
          <div class="form-group mb-3">
            <label class="form-label" for="name">Name</label>
            <input class="form-control" name="name" type="text" id="name" required/>
          </div>
          <div class="form-group mb-3">
            <label class="form-label" for="email">Email Address</label>
            <input class="form-control" name="email" type="email" id="email-address" required />
          </div>
          <div class="form-group mb-3">
            <label class="form-label" for="phonenumber">Phone Number</label>
            <input class="form-control" name="phonenumber" type="tel" id="phonenumber" />
          </div>
          <div class="form-group mb-3">
            <label class="form-label" for="amount">Amount</label>
            <input class="form-control" name="amount" type="tel" id="amount" value="<?php echo $price; ?>" required disabled/>
          </div>
          <div class="form-group mb-3">
            <label class="form-label" for="business_name">Business Name</label>
            <input class="form-control" name="business_name" type="text" id="business_name" />
          </div>
          <div class="form-submit">
            <button class="btn btn-primary w-100" name="save" type="submit" onclick="payWithPaystack()"> Pay </button>
        </div>
			  <input type="hidden" name="" id="ref" value="<?= time(); ?>">
			  <input type="hidden" name="" id="plan" value="<?= $_GET['sub'] ?>">
      </form>
      </div>
    </div>
      </div>
    </section>

  </main><!-- End #main -->

<!--  <script src="https://js.paystack.co/v1/inline.js"></script>-->
<!--  <script src="pay/js/main.js"></script>-->

<script src="https://checkout.flutterwave.com/v3.js"></script>
<script src="pay/js/flw.js"></script>
  <?php
    require_once('includes/footer.php')
  ?>
