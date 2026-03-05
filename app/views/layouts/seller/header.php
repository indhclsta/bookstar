<?php
require_once APP_PATH . '/models/NotificationModel.php';
require_once APP_PATH . '/models/ChatModel.php';

$userId = $_SESSION['user']['id'] ?? 0;

$notifModel = new NotificationModel();
$notifications = $notifModel->getUnreadByUser($userId);
$totalNotif   = $notifModel->countUnread($userId);

$chatModel = new ChatModel();
$totalUnreadChat = $chatModel->getTotalUnread($userId);

$user  = $_SESSION['user'] ?? null;
$name  = $user['name']  ?? 'Guest';
$email = $user['email'] ?? '';
$photo = !empty($user['photo'])
  ? BASE_URL . '/uploads/profile/' . $user['photo']
  : 'https://placehold.co/100x100/png';

// ---------------------
// Halaman yang punya search
// ---------------------
$curController = $_GET['c'] ?? 'seller';
$curMethod     = $_GET['m'] ?? 'index';

$searchPages = [
  'seller' => 'index',
  'sellerProduct'  => ['index', 'category'],
  'sellerCategory' => 'index'
];

$showSearch = false;
if (isset($searchPages[$curController])) {
  $methods = is_array($searchPages[$curController]) ? $searchPages[$curController] : [$searchPages[$curController]];
  if (in_array($curMethod, $methods)) {
    $showSearch = true;
  }
}
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
  <!--bootstrap css-->
  <link href="<?= BASE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
      <nav class="navbar navbar-expand align-items-center">
        <div class="btn-toggle">
          <a href="javascript:;"><i class="material-icons-outlined">menu</i></a>
        </div>

        <!-- SEARCH BAR -->
        <?php if ($showSearch): ?>
          <form method="GET" action="<?= BASE_URL ?>">
            <input type="hidden" name="c" value="<?= $curController ?>">
            <input type="hidden" name="m" value="<?= $curMethod ?>">

            <input
              type="text"
              name="q"
              value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
              class="form-control rounded-5 px-4 search-control d-lg-block d-none"
              placeholder="Search...">
          </form>
        <?php endif; ?>
        <!-- END SEARCH BAR -->

        <ul class="navbar-nav gap-1 nav-right-links align-items-center ms-auto">
          <li class="nav-item d-lg-none mobile-search-btn">
            <a class="nav-link" href="javascript:;"><i class="material-icons-outlined">search</i></a>
          </li>

          <!-- CHAT ICON -->
          <li class="nav-item">
            <a href="<?= BASE_URL ?>/?c=sellerChat&m=index" class="nav-link position-relative" title="Chat">
              <i class="material-icons-outlined fs-4">chat</i>
              <?php if ($totalUnreadChat > 0): ?>
                <span class="badge-notify"><?= $totalUnreadChat ?></span>
              <?php endif; ?>
            </a>
          </li>

          <!-- NOTIFICATIONS -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" data-bs-auto-close="outside"
              data-bs-toggle="dropdown" href="javascript:;"><i class="material-icons-outlined">notifications</i>
              <span class="badge-notify"><?= $totalNotif ?></span>
            </a>
            <div class="dropdown-menu dropdown-notify dropdown-menu-end shadow">
              <div class="px-3 py-1 d-flex align-items-center justify-content-between border-bottom">
                <h5 class="notiy-title mb-0">Notifications</h5>
              </div>
              <div class="notify-list">
                <?php if (empty($notifications)): ?>
                  <div class="p-3 text-center text">Tidak ada notifikasi</div>
                <?php else: ?>
                  <?php foreach ($notifications as $notif): ?>
                    <a class="dropdown-item border-bottom py-2"
                      href="<?= BASE_URL ?>/?c=notification&m=read&id=<?= $notif['id'] ?>&redirect=<?= urlencode($notif['link']) ?>">
                      <div class="d-flex align-items-center gap-3">
                        <div class="user-wrapper bg-primary bg-opacity-10">
                          <i class="material-icons-outlined">shopping_cart</i>
                        </div>
                        <div>
                          <h6 class="mb-0"><?= htmlspecialchars($notif['title']) ?></h6>
                          <p class="mb-0 small"><?= htmlspecialchars($notif['message']) ?></p>
                          <small class="text"><?= date('d M Y H:i', strtotime($notif['created_at'])) ?></small>
                        </div>
                      </div>
                    </a>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </li>

          <!-- USER PROFILE -->
          <li class="nav-item dropdown">
            <a href="javascript:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
              <img src="<?= $photo ?>" class="rounded-circle p-1 border" width="45" height="45" alt="User">
            </a>

            <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
              <div class="dropdown-item text-center">
                <img src="<?= $photo ?>" class="rounded-circle p-1 shadow mb-2" width="80" height="80">
                <?php if ($email): ?>
                  <small class="text">
                    <h6><?= htmlspecialchars($name ?? '') ?></h6>
                  </small>
                <?php endif; ?>
              </div>
              <hr class="dropdown-divider">
              <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= BASE_URL ?>/?c=seller&m=profile">
                <i class="material-icons-outlined">person_outline</i>
                Profile
              </a>
              <hr class="dropdown-divider">
              <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= BASE_URL ?>/?c=auth&m=logout">
                <i class="material-icons-outlined">power_settings_new</i>
                Logout
              </a>
            </div>
          </li>
        </ul>
      </nav>
    </header>

    <?php if ($showSearch): ?>
      <!-- SEARCH JS -->
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          const input = document.getElementById('seller-search-input');
          const resultsDiv = document.getElementById('seller-search-results');
          const closeBtn = document.getElementById('seller-search-close');

          input.addEventListener('input', function() {
            const query = this.value.trim();
            if (!query) {
              resultsDiv.style.display = 'none';
              resultsDiv.innerHTML = '';
              return;
            }

            fetch(`<?= BASE_URL ?>/?c=seller&m=search&q=` + encodeURIComponent(query))
              .then(res => res.json())
              .then(data => {
                if (data.length === 0) {
                  resultsDiv.innerHTML = '<div class="p-2">No results found</div>';
                } else {
                  resultsDiv.innerHTML = data.map(seller => `
                        <div class="p-2 border-bottom d-flex align-items-center gap-2">
                            <img src="${seller.photo}" width="32" height="32" class="rounded-circle">
                            <div>
                                <strong>${seller.name}</strong><br>
                                <small>${seller.email} | ${seller.product_count} Produk</small>
                            </div>
                        </div>
                    `).join('');
                }
                resultsDiv.style.display = 'block';
              });
          });

          closeBtn.addEventListener('click', function() {
            input.value = '';
            resultsDiv.style.display = 'none';
          });

          document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !resultsDiv.contains(e.target)) {
              resultsDiv.style.display = 'none';
            }
          });
        });
      </script>
    <?php endif; ?>