<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Log in</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"
    />

      <link href="assets/css/style.css" rel="stylesheet">
  </head>
  <body>
    <div class="row">
      <div
        class="middle-container col-10 mx-auto mt-4 rounded lg-shadow-lg px-2"
      >
      <div class=" d-md-none py-2">
    </div>
        <div class="col-6 col-md d-flex flex-column justify-content-center d-none d-lg-block rounded registration-background">
          <div class="login-image-container h-100 mx-auto rounded ">
              <div class="sign-up-text-container">
                  <h1 class="text-center text-light fs-4">Login to your Account</h1>
                
                  <h1 class="text-center fs-1">YENREACH.COM</h1>
                  <p class="text-center text-white">The Fastest Growing Business Directory Platform in Nigeria</p>
              </div>
              <div class="w-75 d-flex align-items-center justify-content-center">
                  <button class="btn px-5 py-3 shadow button-active shadow ">Log in</button>
                  <a href="auth" class="btn button px-5 py-3 shadow">Sign Up</a>
                  <!-- <div class="col-12 ">
                    <p class="small mb-0 text-center"><a >Have an Account? Login</a></p>
                </div> -->
              </div>
           
          </div>
        </div>
        <div class="col-lg-5 col-12 mx-auto form-container d-flex align-items-center rounded ">
        
                    
                                        <!-- <form role="form" action="signup" method="POST" class="col-10  mx-auto needs-validation py-3"> -->
                                            <form role="form" action="auth?page=<?php echo $page; ?>" method="POST" class="col-10 h-75 login-form mx-auto g-3 needs-validation d-flex flex-column justify-content-evenly ">
                                                
                                                
                                                <div>

                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <a href="../index.php" class="logo d-flex align-items-center w-auto">
                                                            <img src="../assets/img/logo.png" alt="" width="auto">
                                                        </a>
                                                    </div>
                                                     <!-- End Logo  -->
                                                    <div class="pb-2">
                                                        <h5 class="card-title text-center pb-0 fs-4">Login to your Account</h5>
                                                    </div> 
                                                    
                                                </div>
                                                
                                                           
                                                <div class="col-lg-11 col-md-6 mx-auto h-50 d-flex flex-column align-content-center justify-content-around">
                                                        <div class="col-12">
                                                            <label for="login_username" class="form-label">Email address</label>
                                                            <div class="input-group has-validation">
                                                                <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person-circle"></i></span>
                                                                <input type="text" name="username" class="form-control" id="login_username" placeholder="Your email address" required>
                                                                <div class="invalid-feedback">Please enter your Email Address.</div>
                                                            </div>
                                                        </div>
                                    
                                                        <div class="col-12 py-3">
                                                            <label for="yourPassword" class="form-label">Password</label>
                                                            <div class="input-group has-validation mb-2">
                                                                <span class="input-group-text" id="inputGroupPrepend">
                                                                    <i class="bi bi-lock"></i></span>
                                                                    <input type="password" name="password" class="form-control password" id="login_password" placeholder="Your password" required>
                                                                    <span class="input-group-text password-icon" id="inputGroupAppend">
                                                                        <i class="bi bi-eye"></i>
                                                                    </span>
                                                                <div class="invalid-feedback">Please enter your password!</div>
                                                            </div>
                                                        </div>
                                    
                                                        <div class="col-12 py-2">
                                                            <button class="btn w-100 text-white" style="background: #00C853;" id="login_submit" name="submit" type="submit">Login</button>
                                                        </div>
                                                        <div class="col-12 ">
                                                            <p class="small mb-0 text-center"><a href="signup">Create an account</a> || <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalToggle">Forgot Password</a></p>
                                                        </div>


                                                        
                                                            
                                                    
                                                        
                                                
                                        </div>
                                          
                                        
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>    
            </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>

        <script src="assets/js/main.js"></script>
  </body>
</html>
