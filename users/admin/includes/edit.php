<?php
  require_once('../../../config/connect.php');
  if(isset($_GET['user'])) {
    $id = $_GET['user'];
    $query = mysqli_query($link, "SELECT * FROM `users` WHERE `id`='$id'");
    $result = mysqli_fetch_assoc($query);
    $query2 = mysqli_query($link, "SELECT `phonenumber` FROM `businesses` WHERE `user`='$id'");
    $result2 = mysqli_fetch_assoc($query2);

?>
<div class="mx-5 my-2 mt-2">
    <div class="row mb-3">
      <label for="company" class="col-md-4 col-lg-3 col-form-label">Name</label>
      <div class="col-md-8 col-lg-9">
        <input name="company" type="text" class="form-control" id="company" value="<?php echo $result['name']; ?>">
        <input class="visually-hidden" name="id" type="text" value="<?php echo $result['id']; ?>">
      </div>
    </div>

    <div class="row mb-3">
      <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
      <div class="col-md-8 col-lg-9">
        <input name="phone" type="text" class="form-control" id="Phone" value="<?php echo $result2['phonenumber']; ?>">
        <div class="form-text">Phone Number Format: +234XXXXXXXX</div>
      </div>
    </div>

    <div class="row mb-3">
      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
      <div class="col-md-8 col-lg-9">
        <input name="email" type="email" class="form-control" id="Email" value="<?php echo $result['email']; ?>">
      </div>
    </div>

    <div class="row mb-3">
      <label for="company" class="col-md-4 col-lg-3 col-form-label">Verification</label>
      <div class="col-md-8 col-lg-9">
        <select class="form-select my-3" name="verify" id="select_bus" style="width: 100%;" aria-label="select example" >
          <option <?php if($result['confirmed_email']==1){echo 'selected';}  ?> value='1'>Verified</option>
          <option <?php if($result['confirmed_email']==0){echo 'selected';}  ?> value='0'>Unverify</option>
        </select>
        <input class="visually-hidden" name="id" type="text" value="<?php echo $result['id']; ?>">
        <input class="visually-hidden" name="bus" type="text" value="<?php echo $result['business']; ?>">
      </div>
    </div>

    <div class="col-12">
      <button class="btn w-100 text-white" style="background: #00C853;" id="send" name="send" type="submit">Save Changes</button>
    </div>
</div>
<?php }
else if(isset($_GET['bus'])) {
  $id = $_GET['bus'];
  $query = mysqli_query($link, "SELECT * FROM `businesses` WHERE `id`='$id'");
  $row = mysqli_fetch_assoc($query);
?>
<div class="mx-5 my-2 mt-2">
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Business Name</label>
    <div class="col-md-8 col-lg-9">
      <input name="company" type="text" class="form-control" id="company" value="<?php echo $row['name']; ?>">
      <input class="visually-hidden" name="id" type="text" value="<?php echo $row['id']; ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="about" class="col-md-4 col-lg-3 col-form-label">Business Description</label>
    <div class="col-md-8 col-lg-9">
      <textarea name="about" class="form-control" id="about" style="height: 200px" ><?php echo $row['description']; ?></textarea>
    </div>
  </div>

  <div class="row mb-3">
    <label for="Country" class="col-md-4 col-lg-3 col-form-label">State</label>
    <div class="col-md-8 col-lg-9">
      <input name="state" type="text" class="form-control" id="Country" value="<?php echo  $row['state']; ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
    <div class="col-md-8 col-lg-9">
      <input name="address" type="text" class="form-control" id="Address" value="<?php echo $row['address']; ?>" required>
    </div>
  </div>

  <div class="row mb-3">
    <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
    <div class="col-md-8 col-lg-9">
      <input name="phone" type="text" class="form-control" id="Phone" value="<?php echo $row['phonenumber']; ?>">
      <div class="form-text">Phone Number Format: +234XXXXXXXX</div>
    </div>
  </div>

  <div class="row mb-3">
    <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
    <div class="col-md-8 col-lg-9">
      <input name="email" type="email" class="form-control" id="Email" value="<?php echo $row['email']; ?>" >
      <div class="form-text">Link Format: http://yourwebsite.com</div>
    </div>
  </div>

  <div class="row mb-3">
    <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Whatsapp Number</label>
    <div class="col-md-8 col-lg-9">
      <input name="whatsapp" type="text" class="form-control" id="Twitter" value="<?php echo $row['whatsapp']; ?>" >
      <div class="form-text">Phone Number Format: +234XXXXXXXX</div>
    </div>
  </div>

  <div class="row mb-3">
    <label for="experience" class="col-md-4 col-lg-3 col-form-label">Experience</label>
    <div class="col-md-8 col-lg-9">
      <input name="experience" type="text" class="form-control" id="experience" value="<?php echo $row['experience']; ?>" >
    </div>
  </div>

  <div class="row mb-3">
    <label for="Job" class="col-md-4 col-lg-3 col-form-label">Website Link</label>
    <div class="col-md-8 col-lg-9">
      <input name="web_link" type="text" class="form-control" id="Job" value="<?php echo $row['website_link']; ?>" >
      <div class="form-text">Link Format: http://yourwebsite.com</div>
    </div>
  </div>


  <div class="row mb-3">
    <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label>
    <div class="col-md-8 col-lg-9">
      <?php if($row['subscription_type']>1) { ?>
        <input name="facebook" type="text" class="form-control" id="Facebook" value="<?php echo $row['facebook_link']; ?>" >
        <div class="form">Ensure the link has https:// in it.</div>
      <?php } else { echo '<a href="https://yenreach.com/optin.php" class="btn btn-warning"><i class="bi bi-lock"></i> Upgrade to Unlock Feature!</a>';} ?>
      </div>
    </div>

    <div class="row mb-3">
      <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
      <div class="col-md-8 col-lg-9">
        <?php if($row['subscription_type']>1 ) { ?>
          <input name="instagram" type="text" class="form-control" id="Instagram" value="<?php echo $row['instagram_link']; ?>" >
          <div class="form">Ensure the link has https:// in it.</div>
        <?php } else { echo '<a href="https://yenreach.com/optin.php" class="btn btn-warning"><i class="bi bi-lock"></i> Upgrade to Unlock Feature!</a>';} ?>
        </div>
      </div>

      <div class="row mb-3">
        <label for="youtube" class="col-md-4 col-lg-3 col-form-label">Youtube Link</label>
        <div class="col-md-8 col-lg-9">
          <?php if($row['subscription_type']>1) { ?>
            <input name="youtube" type="text" class="form-control" id="youtube" value="<?php echo $row['youtube_link']; ?>" >
            <div class="form">Ensure the link has https:// in it.</div>
          <?php } else { echo '<a href="https://yenreach.com/optin.php" class="btn btn-warning"><i class="bi bi-lock"></i> Upgrade to Unlock Feature!</a>';} ?>
          </div>
        </div>

        <div class="row mb-3">
          <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin Link</label>
          <div class="col-md-8 col-lg-9">
            <?php if($row['subscription_type']>1) { ?>
              <input name="linkedin" type="text" class="form-control" id="Linkedin" value="<?php echo $row['linkedin_link']; ?>" >
              <div class="form">Ensure the link has https:// in it.</div>
            <?php } else { echo '<a href="https://yenreach.com/optin.php" class="btn btn-warning"><i class="bi bi-lock"></i> Upgrade to Unlock Feature!</a>';} ?>
            </div>
          </div>

          <?php if(strtolower($row['category'])!="job seekers"){ ?>
            <div class="row mb-3">
              <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Working Hours</label>
              <div class="col-md-8 col-lg-9">
                <ul>
                  <li>
                    <div class="input-group">
                      <span class="input-group-text" id="basic-addon1">Opening</span>
                      <input type="time" class="form-control" aria-describedby="basic-addon1" name="opening">
                    </div>
                  </li>
                  <li>
                    <div class="input-group">
                      <span class="input-group-text" id="basic-addon2">Closing</span>
                      <input type="time" class="form-control" aria-describedby="basic-addon2" name="closing">
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            <div class="row mb-3">
              <label for="days" class="col-md-4 col-lg-3 col-form-label">Days of The Week</label>
              <div class="col-md-8 col-lg-9 form-floating">
                <input name="days" type="text" class="form-control" id="days" placeholder="e.g Monday - Tuesday">
                <label for="days">What days of the week is your business open e.g Monday - Tuesday?</label>
              </div>
            </div>
           <?php } ?>



  <div class="col-12">
    <button class="btn w-100 text-white" style="background: #00C853;" id="send" name="send" type="submit">Save Changes</button>
  </div>
</div>
<?php } else if(isset($_GET['sub'])) {
  $id = $_GET['sub'];
  $query = mysqli_query($link, "SELECT * FROM `subscriptions` WHERE `id`='$id'");
  $result = mysqli_fetch_assoc($query);

?>
<div class="mx-5 my-2 mt-2">
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Subscription</label>
    <div class="col-md-8 col-lg-9">
      <select class="form-select my-3" name="type" id="select_bus" style="width: 100%;" aria-label="select example" >
        <option value="choose" selected>Choose</option>
        <option value="4">Premium</option>
        <option value="3">Gold</option>
        <option value="2">Silver</option>
        <option value="1">Free</option>
      </select>
      <input class="visually-hidden" name="id" type="text" value="<?php echo $result['id']; ?>">
      <input class="visually-hidden" name="bus" type="text" value="<?php echo $result['business']; ?>">
    </div>
  </div>

  <div class="col-12">
    <button class="btn w-100 text-white" style="background: #00C853;" id="send" name="send" type="submit">Save Changes</button>
  </div>
</div>
<?php } else if(isset($_GET['cat'])) {?>
<div class="mx-5 my-2 mt-2">
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Category</label>
    <div class="col-md-8 col-lg-9">
      <input name="category" type="text" class="form-control" id="company" >
    </div>
  </div>

  <div class="col-12">
    <button class="btn w-100 text-white" style="background: #00C853;" id="send" name="send" type="submit">Save Changes</button>
  </div>
</div>
<?php } else if(isset($_GET['faq']) && $_GET['faq']!="") {
  $id = $_GET['faq'];
  $query = mysqli_query($link, "SELECT * FROM `faqs` WHERE `id`='$id'");
  $result = mysqli_fetch_assoc($query);
  ?>
<div class="mx-5 my-2 mt-2">
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Question</label>
    <div class="col-md-8 col-lg-9">
      <input name="question" type="text" class="form-control" id="company" value="<?php echo $result['question']; ?>" >
      <input class="visually-hidden" name="id" type="text" class="form-control" id="company" value="<?php echo $result['id']; ?>" >
    </div>
  </div>
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Answer</label>
    <div class="col-md-8 col-lg-9">
      <textarea name="answer" type="text" class="form-control" id="company"  style="height: 300px;" ><?php echo $result['answer']; ?></textarea>
    </div>
  </div>

  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label"></label>
    <div class="col-md-8 col-lg-9">
      <button class="btn w-100 text-white" style="background: #00C853;" id="save" name="save" type="submit">Save Changes</button>
    </div>
  </div>
</div>
<?php } else if(isset($_GET['faq']) && $_GET['faq']=="") {?>
<div class="mx-5 my-2 mt-2">
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Question</label>
    <div class="col-md-8 col-lg-9">
      <input name="question" type="text" class="form-control" id="company" >
    </div>
  </div>
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Answer</label>
    <div class="col-md-8 col-lg-9">
      <textarea name="answer" type="text" class="form-control" id="company"  style="height: 200px;" ></textarea>
    </div>
  </div>

  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label"></label>
    <div class="col-md-8 col-lg-9">
      <button class="btn w-100 text-white" style="background: #00C853;" id="send" name="send" type="submit">Submit</button>
    </div>
  </div>
</div>
<?php } else if(isset($_GET['blo']) && $_GET['blo']=="") {?>
<div class="mx-5 my-2 mt-2">
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Title</label>
    <div class="col-md-8 col-lg-9">
      <input name="title" type="text" class="form-control" id="company" >
    </div>
  </div>
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Content</label>
    <div class="col-md-8 col-lg-9">
      <textarea name="answer" type="text" class="form-control" id="company"  style="height: 300px;" ></textarea>
    </div>
  </div>
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Tags</label>
    <div class="col-md-8 col-lg-9">
      <input name="tag" type="text" class="form-control" id="company">
      <div class="form-text">Seperate entries by comma (,)</div>
    </div>
  </div>
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Category</label>
    <div class="col-md-8 col-lg-9">
      <input name="category" type="text" class="form-control" id="company">
      <div class="form-text">Seperate entries by comma (,)</div>
    </div>
  </div>

  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label"></label>
    <div class="col-md-8 col-lg-9">
      <button class="btn w-100 text-white" style="background: #00C853;" id="send" name="send" type="submit">Submit</button>
    </div>
  </div>
</div>
<?php } else if(isset($_GET['blo']) && $_GET['blo']!="") {
  $id = $_GET['blo'];
  $query = mysqli_query($link, "SELECT * FROM `blog` WHERE `id`='$id'");
  $result = mysqli_fetch_assoc($query);
  ?>
<div class="mx-5 my-2 mt-2">
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Title</label>
    <div class="col-md-8 col-lg-9">
      <input name="title" type="text" class="form-control" id="company" value="<?php echo $result['title']; ?>" >
      <input class="visually-hidden" name="id" type="text" class="form-control" id="company" value="<?php echo $result['id']; ?>" >
    </div>
  </div>
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Content</label>
    <div class="col-md-8 col-lg-9">
      <textarea name="answer" type="text" class="form-control" id="company"  style="height: 300px;" ><?php echo $result['content']; ?></textarea>
    </div>
  </div>
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Tags</label>
    <div class="col-md-8 col-lg-9">
      <input name="tag" type="text" class="form-control" id="company" value="<?php echo $result['tags']; ?>" >
      <div class="form-text">Seperate entries by comma (,)</div>
    </div>
  </div>
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Category</label>
    <div class="col-md-8 col-lg-9">
      <input name="category" type="text" class="form-control" id="company" value="<?php echo $result['category']; ?>" >
      <div class="form-text">Seperate entries by comma (,)</div>
    </div>
  </div>

  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label"></label>
    <div class="col-md-8 col-lg-9">
      <button class="btn w-100 text-white" style="background: #00C853;" id="save" name="save" type="submit">Save Changes</button>
    </div>
  </div>
</div>
<?php } else if(isset($_GET['not'])) {
  $id = $_GET['not'];
  $query = mysqli_query($link, "SELECT * FROM `messages` WHERE `id`='$id'");
  $result = mysqli_fetch_assoc($query);
  ?>
<div class="mx-5 my-2 mt-2">
  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label">Status</label>
    <div class="col-md-8 col-lg-9">
      <select class="form-select my-3" name="status" id="select_bus" style="width: 100%;" aria-label="select example" >
        <option value="choose" selected>Choose</option>
        <option value="0">Pending</option>
        <option value="1">Treated</option>
      </select>
      <input class="visually-hidden" name="id" type="text" class="form-control" id="company" value="<?php echo $result['id']; ?>" >
    </div>
  </div>

  <div class="row mb-3">
    <label for="company" class="col-md-4 col-lg-3 col-form-label"></label>
    <div class="col-md-8 col-lg-9">
      <button class="btn w-100 text-white" style="background: #00C853;" id="save" name="save" type="submit">Save Changes</button>
    </div>
  </div>
</div>
<?php } ?>
