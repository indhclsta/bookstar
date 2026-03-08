<?php
require_once APP_PATH . '/models/CartModel.php';
require_once APP_PATH . '/models/ChatModel.php';

$chatModel = new ChatModel();
$unreadChat = 0;

if (isset($_SESSION['user'])) {
  $unreadChat = $chatModel->getTotalUnread($_SESSION['user']['id']);
}

$cartCount = 0;
if (isset($_SESSION['user'])) {
  $cartModel = new CartModel();
  $cartCount = $cartModel->countByUser($_SESSION['user']['id']);
}

require_once APP_PATH . '/models/NotificationModel.php';

$notifications = [];
$unreadCount  = 0;

if (isset($_SESSION['user'])) {
  $notificationModel = new NotificationModel();
  $notifications = $notificationModel->getByUser($_SESSION['user']['id']);
  $unreadCount  = $notificationModel->countUnread($_SESSION['user']['id']);
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/chat.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            <div class="search-popup p-3">
              <div class="card rounded-4 overflow-hidden">
                <div class="card-header d-lg-none">
                  <div class="position-relative">
                   
                  </div>
                </div>
                <div class="card-body search-content">
                  <div class="d-flex align-items-start flex-wrap gap-2 kewords-wrapper">
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

          <?php if (isset($_SESSION['user'])): ?>
            <li class="nav-item">
              <a href="<?= BASE_URL ?>/?c=customerChat&m=index"
                class="nav-link position-relative"
                title="Chat">

                <i class="material-icons-outlined fs-4">chat</i>

                <?php if ($unreadChat > 0): ?>
                  <span class="badge-notify">
                    <?= $unreadChat ?>
                  </span>
                <?php endif; ?>

              </a>
            </li>
          <?php endif; ?>


          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" data-bs-auto-close="outside"
              data-bs-toggle="dropdown" href="javascript:;"><i class="material-icons-outlined">notifications</i>
              <?php if ($unreadCount > 0): ?>
                <span class="badge-notify"><?= $unreadCount ?></span>
              <?php endif; ?>

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
                <?php if (empty($notifications)): ?>
                  <div class="p-3 text-center text">
                    Tidak ada notifikasi
                  </div>
                <?php else: ?>

                  <?php
                  $hasUnread = false;
                  foreach ($notifications as $notif) {
                    if (empty($notif['is_read']) || $notif['is_read'] == 0) {
                      $hasUnread = true;
                      break;
                    }
                  }
                  ?>

                  <?php if (!$hasUnread): ?>
                    <div class="p-3 text-center text">
                      Tidak ada notifikasi baru
                    </div>
                  <?php else: ?>

                    <?php foreach ($notifications as $notif): ?>
                      <?php if (!empty($notif['is_read']) && $notif['is_read'] == 1) continue; ?>

                      <a class="dropdown-item border-bottom py-2"
                        href="<?= BASE_URL ?>/?c=notification&m=read&id=<?= $notif['id'] ?>&redirect=<?= urlencode($notif['link']) ?>">

                        <div class="d-flex align-items-center gap-3">
                          <div class="user-wrapper bg-primary bg-opacity-10">
                            <i class="material-icons-outlined">shopping_cart</i>
                          </div>
                          <div>
                            <h6 class="mb-0"><?= htmlspecialchars($notif['title']) ?></h6>
                            <p class="mb-0 small"><?= htmlspecialchars($notif['message']) ?></p>
                            <small class="text">
                              <?= date('d M Y H:i', strtotime($notif['created_at'])) ?>
                            </small>
                          </div>
                        </div>

                      </a>
                    <?php endforeach; ?>

                  <?php endif; ?>

                <?php endif; ?>
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
      <style>
        /* Perlebar dropdown notifikasi */
        .dropdown-notify {
          width: 380px !important;
          max-width: 95vw;
        }

        /* Supaya bisa scroll kalau banyak */
        .notify-list {
          max-height: 400px;
          overflow-y: auto;
        }

        /* IZINKAN TEKS TURUN KE BAWAH */
        .notify-list .dropdown-item {
          white-space: normal !important;
          word-break: break-word;
        }

        /* Supaya judul & pesan tidak kepotong */
        .notify-list h6,
        .notify-list p {
          white-space: normal !important;
          margin-bottom: 2px;
        }
      </style>
    </header>