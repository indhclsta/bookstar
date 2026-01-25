<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BookStar | E-Commerce</title>
  <!--favicon-->
  <link rel="icon" href="<?= BASE_URL ?>/assets/images/" type="image/png">
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

    <div class="card my-5 col-xl-9 col-xxl-8 mx-auto rounded-4 overflow-hidden p-4">
      <div class="row g-4 align-items-center">
        <div class="col-lg-6 d-flex">
          <div class="card-body">
            <img src="<?= BASE_URL ?>/assets/images/logo2.png" class="mb-4" width="145" alt="">
            <h4 class="fw-bold">Genrate New Password</h4>
            <p class="mb-0">We received your reset password request. Please enter your new password!</p>
            <div class="form-body mt-4">
              <form class="row g-3" method="POST" action="<?= BASE_URL ?>/?c=auth&m=resetPasswordProcess">
                <input type="hidden" name="token" value="<?= $_GET['token'] ?>">
                <div class="col-12">
                  <label class="form-label" for="NewPassword">New Password</label>
                  <input type="text" class="form-control" id="NewPassword" name="password" placeholder="Enter new password" required>
                </div>
                <div class="col-12">
                  <label class="form-label" for="ConfirmPassword">Confirm Password</label>
                  <input type="text" class="form-control" id="ConfirmPassword" name="confirm_password" placeholder="Confirm password" required>
                </div>
                <div class="col-12">
                  <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-grd-danger">Change Password</button>
                    <a href="<?= BASE_URL ?>/?c=auth&m=login" class="btn btn-light">Back to Login</a>
                  </div>
                </div>
              </form>
            </div>

          </div>
        </div>
        <div class="col-lg-6 d-lg-flex d-none">
          <div class="p-3 rounded-4 w-100 d-flex align-items-center justify-content-center bg-grd-danger">
            <img src="<?= BASE_URL ?>/assets/images/auth/reset-password1.png" class="img-fluid" alt="">
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