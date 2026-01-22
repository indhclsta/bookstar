<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Maxton | Bootstrap 5 Admin Dashboard Template</title>
  <!--favicon-->
  <link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon-32x32.png" type="image/png">
  <!-- loader-->
  <link href="<?= BASE_URL ?>/assets/css/pace.min.css" rel="stylesheet">
  <script src="<?= BASE_URL ?>/assets/js/pace.min.js"></script>

  <!--plugins-->
  <link href="<?= BASE_URL ?>/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/plugins/metismenu/metisMenu.min.css">
  <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/plugins/metismenu/mm-vertical.css">
  <!--bootstrap css-->
  <link href="<?= BASE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <!--main css-->
  <link href="<?= BASE_URL ?>/assets/css/bootstrap-extended.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/main.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/blue-theme.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/dark-theme.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/responsive.css" rel="stylesheet">


</head>

<body>


  <!--authentication-->

  <div class="mx-3 mx-lg-0">

    <div class="card my-5 col-xl-9 col-xxl-8 mx-auto rounded-4 overflow-hidden border-3 p-4">
      <div class="row g-4">
        <div class="col-lg-6 d-flex">
          <div class="card-body">
            <img src="<?= BASE_URL ?>/assets/images/logo1.png" class="mb-4" width="145" alt="">
            <h4 class="fw-bold">Get Started Now</h4>

            <div class="form-body mt-4">
              <form class="row g-3" method="POST" action="<?= BASE_URL ?>/?c=auth&m=registerProcess">

                <div class="col-12">
                  <label class="form-label">Full Name</label>
                  <input type="text" name="name" class="form-control" placeholder="User" required>
                </div>

                <div class="col-12">
                  <label class="form-label">NIK</label>
                  <input type="text" name="nik" class="form-control" placeholder="Nik" required>
                </div>

                <div class="col-12">
                  <label class="form-label">Email Address</label>
                  <input type="email" name="email" class="form-control" placeholder="example@user.com" required>
                </div>

                <div class="col-12">
                  <label class="form-label">Password</label>
                  <div class="input-group">
                    <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
                  </div>
                </div>

                <div class="col-12">
                  <label class="form-label">Confirm Password</label>
                  <div class="input-group">
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                  </div>
                </div>

                <div class="col-12">
                  <label class="form-label">Role</label>
                  <select name="role_id" class="form-select" required>
                    <option value="">--Select Role--</option>
                    <option value="2">Seller</option>
                    <option value="3">Customer</option>
                  </select>
                </div>

                <div class="col-12">
                  <label class="form-label">Address</label>
                  <input type="text" name="address" class="form-control" placeholder="Address" required>
                </div>

                <div class="col-12">
                  <div class="d-grid">
                    <button type="submit" class="btn btn-grd-info">Register</button>
                  </div>
                </div>
                <div class="col-12">
                  <div class="text-start">
                    <p class="mb-0">Already have an account? <a href="<?= BASE_URL ?>/?c=auth&m=login">Sign in here</a>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-6 d-lg-flex d-none">
          <div class="p-3 rounded-4 w-100 d-flex align-items-center justify-content-center bg-grd-info">
            <img src="<?= BASE_URL ?>/assets/images/auth/register1.png" class="img-fluid" alt="">
          </div>
        </div>
      </div><!--end row-->
    </div>

  </div>

  <!--authentication-->




  <!--plugins-->
  <script src="<?= BASE_URL ?>/assets/js/jquery.min.js"></script>

  <script>
    $(document).ready(function() {
      $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr("type") == "text") {
          $('#show_hide_password input').attr('type', 'password');
          $('#show_hide_password i').addClass("bi-eye-slash-fill");
          $('#show_hide_password i').removeClass("bi-eye-fill");
        } else if ($('#show_hide_password input').attr("type") == "password") {
          $('#show_hide_password input').attr('type', 'text');
          $('#show_hide_password i').removeClass("bi-eye-slash-fill");
          $('#show_hide_password i').addClass("bi-eye-fill");
        }
      });
    });
  </script>

</body>

</html>