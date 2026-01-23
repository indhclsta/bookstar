<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>
<?php
$user = $_SESSION['user'] ?? [];

$name = $user['name'] ?? 'User';

$photo = !empty($user['photo'])
  ? BASE_URL . '/uploads/profile/' . $user['photo']
  : 'https://placehold.co/110x110/png';
?>


<main class="main-wrapper">
  <div class="main-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Dashboard</div>

      <div class="ms-auto">

      </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
      <div class="col-xxl-8 d-flex align-items-stretch">
        <div class="card w-100 overflow-hidden rounded-4">
          <div class="card-body position-relative p-4">
            <div class="row">
              <div class="col-12 col-sm-7">
                <div class="d-flex align-items-center gap-3 mb-5">
                  <img src="<?= htmlspecialchars($photo) ?>"
                    class="rounded-circle bg-grd-info p-1"
                    width="60" height="60" alt="user">

                  <div>
                    <p class="mb-0 fw-semibold">Welcome back</p>
                    <h4 class="fw-semibold fs-4 mb-0">
                      <?= htmlspecialchars($name) ?>!
                    </h4>
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-center gap-5">
                <div class="">
                  <h4 class="mb-1 fw-semibold d-flex align-content-center">$65.4K<i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
                  </h4>
                  <p class="mb-3">Today's Sales</p>
                  <div class="progress mb-0" style="height:5px;">
                    <div class="progress-bar bg-grd-success" role="progressbar" style="width: 60%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
                <div class="vr"></div>
                <div class="">
                  <h4 class="mb-1 fw-semibold d-flex align-content-center">78.4%<i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
                  </h4>
                  <p class="mb-3">Growth Rate</p>
                  <div class="progress mb-0" style="height:5px;">
                    <div class="progress-bar bg-grd-danger" role="progressbar" style="width: 60%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-5">
              <div class="welcome-back-img pt-4">
                <img src="<?= BASE_URL ?>/assets/images/gallery/welcome-back-3.png" height="180" alt="">
              </div>
            </div>
          </div><!--end row-->
        </div>
      </div>
    </div>



  </div>
</main>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>