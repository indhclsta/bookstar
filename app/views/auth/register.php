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

  <link rel="icon" href="<?= BASE_URL ?>/assets/images/" type="image/png">
  <link href="<?= BASE_URL ?>/assets/css/pace.min.css" rel="stylesheet">
  <script src="<?= BASE_URL ?>/assets/js/pace.min.js"></script>

  <link href="<?= BASE_URL ?>/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/plugins/metismenu/metisMenu.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/plugins/metismenu/mm-vertical.css">

  <link href="<?= BASE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">

  <link href="<?= BASE_URL ?>/assets/css/bootstrap-extended.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/main.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/blue-theme.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/dark-theme.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/responsive.css" rel="stylesheet">
</head>

<body>

  <div class="mx-3 mx-lg-0">
    <div class="card my-5 col-xl-9 col-xxl-8 mx-auto rounded-4 overflow-hidden border-3 p-4">
      <div class="row g-4">

        <div class="col-lg-6 d-flex">
          <div class="card-body">
            <img src="<?= BASE_URL ?>/assets/images/logo2.png" class="mb-4" width="145">
            <h4 class="fw-bold">Get Started Now</h4>

            <div class="form-body mt-4">
              <!-- ⬇️ TIDAK DIUBAH, HANYA DITAMBAH enctype -->
              <form class="row g-3" method="POST"
                action="<?= BASE_URL ?>/?c=auth&m=registerProcess"
                enctype="multipart/form-data">

                <div class="col-12">
                  <label class="form-label">Full Name</label>
                  <input type="text" name="name" class="form-control" required>
                </div>

                <div class="col-12">
                  <label class="form-label">NIK</label>
                  <input type="text" name="nik" class="form-control" required>
                </div>

                <div class="col-12">
                  <label class="form-label">Email Address</label>
                  <input type="email" name="email" class="form-control" required>
                </div>

                <div class="col-12">
                  <label class="form-label">Phone Number</label>
                  <input type="text" name="no_tlp" class="form-control" required>
                </div>


                <div class="col-12">
                  <label class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" required>
                </div>

                <div class="col-12">
                  <label class="form-label">Confirm Password</label>
                  <input type="password" name="confirm_password" class="form-control" required>
                </div>

                <div class="col-12">
                  <label class="form-label">Role</label>
                  <select name="role_id" class="form-select" required>
                    <option value="">--Select Role--</option>
                    <option value="2">Seller</option>
                    <option value="3">Customer</option>
                  </select>
                </div>

                <!-- NO REKENING -->
                <div class="col-12 d-none" id="rekeningField">
                  <label class="form-label">No Rekening</label>
                  <input type="text" name="no_rekening" class="form-control">
                </div>

                <!-- 🔥 TAMBAHAN: QRIS IMAGE (SELLER) -->
                <div class="col-12 d-none" id="qrisField">
                  <label class="form-label">Upload QRIS</label>
                  <input type="file" name="qris_image" class="form-control" accept="image/*">
                </div>

                <div class="col-12">
                  <label class="form-label">Address</label>
                  <input type="text" name="address" class="form-control" required>
                </div>

                <div class="col-12">
                  <button type="submit" class="btn btn-grd-info w-100">Register</button>
                </div>

                <div class="col-12">
                  <p class="mb-0">
                    Already have an account?
                    <a href="<?= BASE_URL ?>/?c=auth&m=login">Sign in here</a>
                  </p>
                </div>

              </form>
            </div>
          </div>
        </div>

        <div class="col-lg-6 d-lg-flex d-none">
          <div class="p-3 rounded-4 w-100 d-flex align-items-center justify-content-center bg-grd-info">
            <img src="<?= BASE_URL ?>/assets/images/auth/register1.png" class="img-fluid">
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="<?= BASE_URL ?>/assets/js/jquery.min.js"></script>

  <script>
    $(document).ready(function() {
      $('select[name="role_id"]').on('change', function() {
        if ($(this).val() == '2') {
          $('#rekeningField').removeClass('d-none');
          $('#qrisField').removeClass('d-none');

          $('input[name="no_rekening"]').attr('required', true);
          $('input[name="qris_image"]').attr('required', true);
        } else {
          $('#rekeningField').addClass('d-none');
          $('#qrisField').addClass('d-none');

          $('input[name="no_rekening"]').removeAttr('required').val('');
          $('input[name="qris_image"]').removeAttr('required').val('');
        }
      });
    });
  </script>

</body>

</html>