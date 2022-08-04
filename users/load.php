<?php require_once('../config/connect.php');

  if(!empty($_GET['business_id'])) {
    $id = $_GET["business_id"];
  	$query = mysqli_query($link, "SELECT * FROM `businesses` WHERE `id`='$id'");
    $result = mysqli_fetch_assoc($query);
    $user = $result['user'];
    $user_query = mysqli_query($link, "SELECT * FROM `users` WHERE `id`='$user'");
    $user_result = mysqli_fetch_assoc($query);
    $facilities_query = mysqli_query($link, "SELECT * FROM `facilities` WHERE `business`='$id'");
    $facilities_result = mysqli_fetch_assoc($facilities_query);
    $img_query = mysqli_query($link, "SELECT * FROM `images` WHERE `business`='$id'");
    $img_result = mysqli_fetch_assoc($img_query);
    $sub_query = mysqli_query($link, "SELECT * FROM `subscriptions` WHERE `business`='$id'");
    $sub_result = mysqli_fetch_assoc($sub_query);
    $branches_query = mysqli_query($link, "SELECT * FROM `branches` WHERE `business`='$id'");
  ?>
?>


<div class="card">
  <div class="card-body pt-3">
    <!-- Bordered Tabs -->
    <ul class="nav nav-tabs nav-tabs-bordered">

      <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
      </li>

      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
      </li>

      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#add-category">Add Category</button>
      </li>

      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Add Facilities</button>
      </li>

      <?php if($sub_result['subscription_type']>2) { ?>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#add-branch">Add Branch</button>
      </li>
    <?php } ?>

    </ul>
    <div class="tab-content pt-2">

      <div class="tab-pane fade show active profile-overview" id="profile-overview">
        <h5 class="card-title">Description</h5>
        <p class="small fst-italic"><?php echo $result['description']; ?></p>

        <h5 class="card-title">Business Details</h5>

        <div class="row">
          <div class="col-lg-3 col-md-4 label ">Administrator Name</div>
          <div class="col-lg-9 col-md-8"><?php echo $user_result['name']; ?></div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-4 label">Business Name</div>
          <div class="col-lg-9 col-md-8"><?php echo $result['name']; ?></div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-4 label">Categories</div>
          <div class="col-lg-9 col-md-8"><?php echo $result['category']; ?></div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-4 label">State</div>
          <div class="col-lg-9 col-md-8"><?php echo $result['state']; ?></div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-4 label">Address</div>
          <div class="col-lg-9 col-md-8"><?php echo $result['address']; ?></div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-4 label">Phone</div>
          <div class="col-lg-9 col-md-8"><?php echo $result['phonenumber']; ?></div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-4 label">whatsapp Number</div>
          <div class="col-lg-9 col-md-8"><?php echo $result['whatsapp']; ?></div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-4 label">Email</div>
          <div class="col-lg-9 col-md-8"><?php echo $result['email']; ?></div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-4 label">Facilites</div>
          <div class="col-lg-9 col-md-8">
            <ul>
              <?php if($faclities_result['nose_mask']==1) { ?>
              <li>
                <p><?php echo "Nose Mask Required";}?></p>
              </li>
            <?php } ?>
              <?php if($faclities_result['delivery']==1) { ?>
              <li>
                <p><?php echo "Free Delivery Service";?></p>
              </li>
            <?php } ?>
              <?php if($faclities_result['debit']==1) { ?>
              <li>
                <p><?php echo "Bank Transfer and POS available";?></p>
              </li>
            <?php } ?>
              <?php if($faclities_result['parking_space']==1) { ?>
              <li>
                <p><?php echo "Parking Space Available";?></p>
              </li>
            <?php } ?>
            <li>
              <p><?php $facilities_result['others'];?></p>
            </li>
            </ul>
           </div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-4 label">Branches</div>
          <div class="col-lg-9 col-md-8">
            <ul>
              <?php while ($branches_result = mysqli_fetch_assoc($branches_query)) {?>
              <li>
                <p>Manager - <?php $branches_result['manager'] ?></p>
                <p><?php $branches_result['address'] ?></p>
                <p><?php $branches_result['phonenumber'] ?></p>
              </li>
              <?php }  ?>
            </ul>
           </div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-4 label">Subscription Status</div>
          <div class="col-lg-6 col-md-4"><?php if($sub_result['subscription_type']==1) {echo "Free Trial";} else
          if ($sub_result['subscription_type']==2){echo "Silver";} else if ($sub_result['subscription_type']==3) {
            echo "Gold";} else {echo "Premium"; }?><span class="badge bg-success"><?php if ($sub_result['subscription_status']==1) {
              echo "ACTIVE";} else {echo "EXPIRED";}?></span></div>
          <?php if ($sub_result['subscription_type']<4) {?><div class="col-lg-3 col-md-4 mt-2 label"><a href="../optin.php" class="btn btn-warning py-1 px-2">Upgrade</a></div><?php } ?>
        </div>

      </div>

      <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

        <!-- Profile Edit Form -->
        <form>
          <div class="row mb-3">
            <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Add Photo</label>
            <div class="col-md-8 col-lg-9">
              <img src="../assets/img/user.png" alt="Profile">
              <div class="pt-2">
                <div class="col-sm-10">
                  <input class="form-control" type="file" id="formFile" name="business_image" lang="en">
                </div>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Administrator Name</label>
            <div class="col-md-8 col-lg-9">
              <input name="fullName" type="text" class="form-control" id="fullName" value="<?php $user_result['name']; ?>">
            </div>
          </div>

          <div class="row mb-3">
            <label for="company" class="col-md-4 col-lg-3 col-form-label">Business Name</label>
            <div class="col-md-8 col-lg-9">
              <input name="company" type="text" class="form-control" id="company" value="<?php $result['name']; ?>">
            </div>
          </div>

          <div class="row mb-3">
            <label for="about" class="col-md-4 col-lg-3 col-form-label">Business Description</label>
            <div class="col-md-8 col-lg-9">
              <textarea name="about" class="form-control" id="about" style="height: 100px"><?php $result['description']; ?></textarea>
            </div>
          </div>

          <div class="row mb-3">
            <label for="Country" class="col-md-4 col-lg-3 col-form-label">State</label>
            <div class="col-md-8 col-lg-9">
              <input name="state" type="text" class="form-control" id="Country" value="<?php $result['state']; ?>">
            </div>
          </div>

          <div class="row mb-3">
            <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
            <div class="col-md-8 col-lg-9">
              <input name="address" type="text" class="form-control" id="Address" value="<?php $result['address']; ?>">
            </div>
          </div>

          <div class="row mb-3">
            <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
            <div class="col-md-8 col-lg-9">
              <input name="phone" type="text" class="form-control" id="Phone" value="<?php $result['phonenumber']; ?>">
            </div>
          </div>

          <div class="row mb-3">
            <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
            <div class="col-md-8 col-lg-9">
              <input name="email" type="email" class="form-control" id="Email" value="<?php $result['email']; ?>">
            </div>
          </div>
          <?php if($sub_result['subscription_type']>2) { ?>
          <div class="row mb-3">
            <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">whatsapp Number</label>
            <div class="col-md-8 col-lg-9">
              <input name="whatsapp" type="text" class="form-control" id="Twitter" value="<?php $result['whatsapp']; ?>">
            </div>
          </div>
        <?php } ?>
          <div class="row mb-3">
            <label for="Job" class="col-md-4 col-lg-3 col-form-label">Website Link</label>
            <div class="col-md-8 col-lg-9">
              <input name="web_link" type="text" class="form-control" id="Job" value="<?php $result['website_link']; ?>">
            </div>
          </div>

          <?php if($sub_result['subscription_type']>1) { ?>
          <div class="row mb-3">
            <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label>
            <div class="col-md-8 col-lg-9">
              <input name="facebook" type="text" class="form-control" id="Facebook" value="<?php $result['facebook_link']; ?>">
            </div>
          </div>

          <div class="row mb-3">
            <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
            <div class="col-md-8 col-lg-9">
              <input name="instagram" type="text" class="form-control" id="Instagram" value="<?php $result['instagram_link']; ?>">
            </div>
          </div>

          <div class="row mb-3">
            <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Youtube Link</label>
            <div class="col-md-8 col-lg-9">
              <input name="youtube" type="text" class="form-control" id="Linkedin" value="<?php $result['youtube_link']; ?>">
            </div>
          </div>
        <?php } ?>

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

          <div class="text-center">
            <button type="submit" class="btn btn-primary" name="save_profile">Save Changes</button>
          </div>
        </form><!-- End Profile Edit Form -->

      </div>

      <div class="tab-pane fade pt-3" id="profile-settings">

        <!-- Settings Form -->
        <form>

          <div class="row mb-3">
            <div class="col-md-8 col-lg-9">
              <div class="form-check">
                <input name="nose_mask" class="form-check-input" type="checkbox" id="changesMade" <?php if($facilities_result['nose_mask']==1) {echo "checked"} ?>>
                <label class="form-check-label" for="changesMade">
                  Nose Mask Required
                </label>
              </div>
              <div class="form-check">
                <input name="delivery" class="form-check-input" type="checkbox" id="newProducts" <?php if($facilities_result['delivery']==1) {echo "checked"} ?>>
                <label class="form-check-label" for="newProducts">
                  Free Delivery
                </label>
              </div>
              <div class="form-check">
                <input name="parking" class="form-check-input" type="checkbox" id="proOffers" <?php if($facilities_result['parking_space']==1) {echo "checked"} ?>>
                <label class="form-check-label" for="proOffers">
                  Parking Space
                </label>
              </div>
              <div class="form-check">
                 <input name="debit" class="form-check-input" type="checkbox" id="securityNotify" <?php if($facilities_result['debit']==1) {echo "checked"} ?>>
                <label class="form-check-label" for="securityNotify">
                  Accept Card or Transfer
                </label>
              </div>
              <div class="mt-3">
                <label class="col-md-4 col-lg-3 col-form-label">Other Facilities</label>
                <div class="form-floating">
                  <textarea name="others" class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 200px;"></textarea>
                  <label for="floatingTextarea">Write Facilites in New Lines</label>
                </div>
              </div>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary" name="save_facilities">Save Changes</button>
          </div>
        </form><!-- End settings Form -->

      </div>

      <div class="tab-pane fade pt-3" id="add-category">

        <!-- Category Form -->
        <form>

          <div class="row mb-3">
            <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Select Category</label>
            <div class="col-md-8 col-lg-9">
              <select id="category" class="form-select" aria-label="Default select example" onChange="load_it(this);">
                <option selected>Select</option>
                <?php $cat_query = mysqli_query($link, "SELECT `category` FROM `categories`");
                  while($cat_result = mysqli_fetch_assoc($cat_query)) { ?>
                  <option value="<?php echo strtolower($cat_result['category']); ?>"><?php echo strtoupper($cat_result['category']) ?></option>
                <?php } ?>
                <option value="others">Others</option>
              </select>
              <div id="others" class="mt-3">

              </div>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary" name="save_category">Save Changes</button>
          </div>
        </form><!-- End category Form -->

      </div>

      <?php if($sub_result['subscription_type']>2) { ?>
      <div class="tab-pane fade pt-3" id="add-branch">

        <!-- Branch Form -->
        <form action="#" method="post">

          <div class="row mb-3">
            <label for="manager" class="col-md-4 col-lg-3 col-form-label">Manager Name</label>
            <div class="col-md-8 col-lg-9">
              <input name="manager" type="text" class="form-control" id="manager">
            </div>
          </div>

          <div class="row mb-3">
            <label for="address" class="col-md-4 col-lg-3 col-form-label">Address</label>
            <div class="col-md-8 col-lg-9">
              <input name="address" type="text" class="form-control" id="address">
            </div>
          </div>

          <div class="row mb-3">
            <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone Number</label>
            <div class="col-md-8 col-lg-9">
              <input name="phone" type="text" class="form-control" id="phone">
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary" name="save_branch">Save Changes</button>
            <?php
              if(isset($_POST['save_branch'])) {
                
              }
             ?>
          </div>
        </form><!-- End branch Form -->

      </div>
    <?php } ?>

    </div><!-- End Bordered Tabs -->

  </div>
</div>
