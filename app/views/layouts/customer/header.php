<?php
require_once APP_PATH . '/models/CartModel.php';

$cartCount = 0;
if (isset($_SESSION['user'])) {
  $cartModel = new CartModel();
  $cartCount = $cartModel->countByUser($_SESSION['user']['id']);
}
?>
<?php
$user  = $_SESSION['user'] ?? null;

$name  = $user['name']  ?? '';
$email = $user['email'] ?? '';
$photo = !empty($user['photo'])
  ? BASE_URL . '/uploads/profile/' . $user['photo']
  : 'https://placehold.co/100x100/png';
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
  <link href="<?= BASE_URL ?>/assets/plugins/fancy-file-uploader/fancy_fileupload.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/plugins/metismenu/mm-vertical.css">
  <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/assets/plugins/simplebar/css/simplebar.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/chat.css">

  <!--bootstrap css-->
  <link href="<?= BASE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <!--main css-->
  <link href="<?= BASE_URL ?>/assets/css/bootstrap-extended.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/main.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/dark-theme.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/blue-theme.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/semi-dark.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/bordered-theme.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/responsive.css" rel="stylesheet">


</head>

<body>

  <div class="wrapper">

    <header class="top-header">
      <nav class="navbar navbar-expand align-items-center gap-4">
        <div class="btn-toggle">
          <a href="javascript:;"><i class="material-icons-outlined">menu</i></a>
        </div>
        <div class="search-bar flex-grow-1">
          <div class="position-relative">
            <input class="form-control rounded-5 px-5 search-control d-lg-block d-none" type="text" placeholder="Search">
            <span class="material-icons-outlined position-absolute d-lg-block d-none ms-3 translate-middle-y start-0 top-50">search</span>
            <span class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 search-close">close</span>
            <div class="search-popup p-3">
              <div class="card rounded-4 overflow-hidden">
                <div class="card-header d-lg-none">
                  <div class="position-relative">
                    <input class="form-control rounded-5 px-5 mobile-search-control" type="text" placeholder="Search">
                    <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50">search</span>
                    <span class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 mobile-search-close">close</span>
                  </div>
                </div>
                <div class="card-body search-content">
                  <p class="search-title">Recent Searches</p>
                  <div class="d-flex align-items-start flex-wrap gap-2 kewords-wrapper">
                    <a href="javascript:;" class="kewords"><span>Angular Template</span><i
                        class="material-icons-outlined fs-6">search</i></a>
                    <a href="javascript:;" class="kewords"><span>Dashboard</span><i
                        class="material-icons-outlined fs-6">search</i></a>
                    <a href="javascript:;" class="kewords"><span>Admin Template</span><i
                        class="material-icons-outlined fs-6">search</i></a>
                    <a href="javascript:;" class="kewords"><span>Bootstrap 5 Admin</span><i
                        class="material-icons-outlined fs-6">search</i></a>
                    <a href="javascript:;" class="kewords"><span>Html eCommerce</span><i
                        class="material-icons-outlined fs-6">search</i></a>
                    <a href="javascript:;" class="kewords"><span>Sass</span><i
                        class="material-icons-outlined fs-6">search</i></a>
                    <a href="javascript:;" class="kewords"><span>laravel 9</span><i
                        class="material-icons-outlined fs-6">search</i></a>
                  </div>
                  <hr>
                  <p class="search-title">Tutorials</p>
                  <div class="search-list d-flex flex-column gap-2">
                    <div class="search-list-item d-flex align-items-center gap-3">
                      <div class="list-icon">
                        <i class="material-icons-outlined fs-5">play_circle</i>
                      </div>
                      <div class="">
                        <h5 class="mb-0 search-list-title ">Wordpress Tutorials</h5>
                      </div>
                    </div>
                    <div class="search-list-item d-flex align-items-center gap-3">
                      <div class="list-icon">
                        <i class="material-icons-outlined fs-5">shopping_basket</i>
                      </div>
                      <div class="">
                        <h5 class="mb-0 search-list-title">eCommerce Website Tutorials</h5>
                      </div>
                    </div>

                    <div class="search-list-item d-flex align-items-center gap-3">
                      <div class="list-icon">
                        <i class="material-icons-outlined fs-5">laptop</i>
                      </div>
                      <div class="">
                        <h5 class="mb-0 search-list-title">Responsive Design</h5>
                      </div>
                    </div>
                  </div>

                  <hr>
                  <p class="search-title">Members</p>

                  <div class="search-list d-flex flex-column gap-2">
                    <div class="search-list-item d-flex align-items-center gap-3">
                      <div class="memmber-img">
                        <img src="https://placehold.co/110x110/png" width="32" height="32" class="rounded-circle" alt="">
                      </div>
                      <div class="">
                        <h5 class="mb-0 search-list-title ">Andrew Stark</h5>
                      </div>
                    </div>

                    <div class="search-list-item d-flex align-items-center gap-3">
                      <div class="memmber-img">
                        <img src="https://placehold.co/110x110/png" width="32" height="32" class="rounded-circle" alt="">
                      </div>
                      <div class="">
                        <h5 class="mb-0 search-list-title ">Snetro Jhonia</h5>
                      </div>
                    </div>

                    <div class="search-list-item d-flex align-items-center gap-3">
                      <div class="memmber-img">
                        <img src="https://placehold.co/110x110/png" width="32" height="32" class="rounded-circle" alt="">
                      </div>
                      <div class="">
                        <h5 class="mb-0 search-list-title">Michle Clark</h5>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="card-footer text-center bg-transparent">
                  <a href="javascript:;" class="btn w-100">See All Search Results</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <ul class="navbar-nav gap-1 nav-right-links align-items-center">
          <li class="nav-item d-lg-none mobile-search-btn">
            <a class="nav-link" href="javascript:;"><i class="material-icons-outlined">search</i></a>
          </li>

          <ul class="navbar-nav gap-1 nav-right-links align-items-center">
            <li class="nav-item">
              <a href="<?= BASE_URL ?>/?c=cart&m=index"
                class="nav-link position-relative"
                title="Keranjang Belanja">

                <i class="material-icons-outlined fs-4">shopping_cart</i>

                <?php if ($cartCount > 0): ?>
                  <span class="position-absolute top-0 end-0
                   badge rounded-pill bg-danger">
                    <?= $cartCount ?>
                  </span>
                <?php endif; ?>

              </a>
            </li>



            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" data-bs-auto-close="outside"
                data-bs-toggle="dropdown" href="javascript:;"><i class="material-icons-outlined">notifications</i>
                <span class="badge-notify">5</span>
              </a>
              <div class="dropdown-menu dropdown-notify dropdown-menu-end shadow">
                <div class="px-3 py-1 d-flex align-items-center justify-content-between border-bottom">
                  <h5 class="notiy-title mb-0">Notifications</h5>
                  <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle dropdown-toggle-nocaret option" type="button"
                      data-bs-toggle="dropdown" aria-expanded="false">
                      <span class="material-icons-outlined">
                        more_vert
                      </span>
                    </button>
                    <div class="dropdown-menu dropdown-option dropdown-menu-end shadow">
                      <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i
                            class="material-icons-outlined fs-6">inventory_2</i>Archive All</a></div>
                      <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i
                            class="material-icons-outlined fs-6">done_all</i>Mark all as read</a></div>
                      <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i
                            class="material-icons-outlined fs-6">mic_off</i>Disable Notifications</a></div>
                      <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i
                            class="material-icons-outlined fs-6">grade</i>What's new ?</a></div>
                      <div>
                        <hr class="dropdown-divider">
                      </div>
                      <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i
                            class="material-icons-outlined fs-6">leaderboard</i>Reports</a></div>
                    </div>
                  </div>
                </div>
                <div class="notify-list">
                  <div>
                    <a class="dropdown-item border-bottom py-2" href="javascript:;">
                      <div class="d-flex align-items-center gap-3">
                        <div class="">
                          <img src="https://placehold.co/110x110/png" class="rounded-circle" width="45" height="45" alt="">
                        </div>
                        <div class="">
                          <h5 class="notify-title">Congratulations Jhon</h5>
                          <p class="mb-0 notify-desc">Many congtars jhon. You have won the gifts.</p>
                          <p class="mb-0 notify-time">Today</p>
                        </div>
                        <div class="notify-close position-absolute end-0 me-3">
                          <i class="material-icons-outlined fs-6">close</i>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div>
                    <a class="dropdown-item border-bottom py-2" href="javascript:;">
                      <div class="d-flex align-items-center gap-3">
                        <div class="user-wrapper bg-primary text-primary bg-opacity-10">
                          <span>RS</span>
                        </div>
                        <div class="">
                          <h5 class="notify-title">New Account Created</h5>
                          <p class="mb-0 notify-desc">From USA an user has registered.</p>
                          <p class="mb-0 notify-time">Yesterday</p>
                        </div>
                        <div class="notify-close position-absolute end-0 me-3">
                          <i class="material-icons-outlined fs-6">close</i>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div>
                    <a class="dropdown-item border-bottom py-2" href="javascript:;">
                      <div class="d-flex align-items-center gap-3">
                        <div class="">
                          <img src="<?= BASE_URL ?>/assets/images/apps/13.png" class="rounded-circle" width="45" height="45" alt="">
                        </div>
                        <div class="">
                          <h5 class="notify-title">Payment Recived</h5>
                          <p class="mb-0 notify-desc">New payment recived successfully</p>
                          <p class="mb-0 notify-time">1d ago</p>
                        </div>
                        <div class="notify-close position-absolute end-0 me-3">
                          <i class="material-icons-outlined fs-6">close</i>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div>
                    <a class="dropdown-item border-bottom py-2" href="javascript:;">
                      <div class="d-flex align-items-center gap-3">
                        <div class="">
                          <img src="<?= BASE_URL ?>/assets/images/apps/14.png" class="rounded-circle" width="45" height="45" alt="">
                        </div>
                        <div class="">
                          <h5 class="notify-title">New Order Recived</h5>
                          <p class="mb-0 notify-desc">Recived new order from michle</p>
                          <p class="mb-0 notify-time">2:15 AM</p>
                        </div>
                        <div class="notify-close position-absolute end-0 me-3">
                          <i class="material-icons-outlined fs-6">close</i>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div>
                    <a class="dropdown-item border-bottom py-2" href="javascript:;">
                      <div class="d-flex align-items-center gap-3">
                        <div class="">
                          <img src="https://placehold.co/110x110/png" class="rounded-circle" width="45" height="45" alt="">
                        </div>
                        <div class="">
                          <h5 class="notify-title">Congratulations Jhon</h5>
                          <p class="mb-0 notify-desc">Many congtars jhon. You have won the gifts.</p>
                          <p class="mb-0 notify-time">Today</p>
                        </div>
                        <div class="notify-close position-absolute end-0 me-3">
                          <i class="material-icons-outlined fs-6">close</i>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div>
                    <a class="dropdown-item py-2" href="javascript:;">
                      <div class="d-flex align-items-center gap-3">
                        <div class="user-wrapper bg-danger text-danger bg-opacity-10">
                          <span>PK</span>
                        </div>
                        <div class="">
                          <h5 class="notify-title">New Account Created</h5>
                          <p class="mb-0 notify-desc">From USA an user has registered.</p>
                          <p class="mb-0 notify-time">Yesterday</p>
                        </div>
                        <div class="notify-close position-absolute end-0 me-3">
                          <i class="material-icons-outlined fs-6">close</i>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </li>

            <li class="nav-item dropdown">
              <a href="javascript:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
                <img src="<?= $photo ?>" class="rounded-circle p-1 border" width="45" height="45" alt="User">
              </a>

              <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">

                <div class="dropdown-item text-center">
                  <img src="<?= $photo ?>" class="rounded-circle p-1 shadow mb-2" width="80" height="80">
                  <?php if ($email): ?>
                    <small class="text-muted">
                      <h6><?= htmlspecialchars($name ?? '') ?></h6>
                    </small>
                  <?php endif; ?>
                </div>
                <hr class="dropdown-divider">
                <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                  href="<?= BASE_URL ?>/?c=customer&m=profile">
                  <i class="material-icons-outlined">person_outline</i>
                  Profile
                </a>

                <hr class="dropdown-divider">

                <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                  href="<?= BASE_URL ?>/?c=auth&m=logout">
                  <i class="material-icons-outlined">power_settings_new</i>
                  Logout
                </a>
              </div>
            </li>
          </ul>

      </nav>
    </header>