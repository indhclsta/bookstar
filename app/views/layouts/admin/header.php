<?php
$user = $_SESSION['user'] ?? null;

$photo = (!empty($user['photo']))
  ? BASE_URL . '/uploads/profile/' . $user['photo']
  : 'https://placehold.co/110x110/png';

$name  = $user['name'] ?? 'Guest';
$email = $user['email'] ?? '';
?>

<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Maxton | Bootstrap 5 Admin Dashboard Template</title>

  <link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon-32x32.png" type="image/png">

  <link href="<?= BASE_URL ?>/assets/css/pace.min.css" rel="stylesheet">
  <script src="<?= BASE_URL ?>/assets/js/pace.min.js"></script>

  <link href="<?= BASE_URL ?>/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/plugins/metismenu/metisMenu.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/plugins/metismenu/mm-vertical.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet">

  <link href="<?= BASE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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

        <!-- ================= SEARCH BAR ================= -->
        <div class="search-bar flex-grow-1">
          <div class="position-relative">

            <!-- DESKTOP SEARCH -->
            <input
              class="form-control rounded-5 px-5 search-control d-lg-block d-none"
              type="text"
              placeholder="Search..."
              onkeydown="if(event.key==='Enter'){window.location='<?= BASE_URL ?>/?c=search&q='+encodeURIComponent(this.value)}">

            <span class="material-icons-outlined position-absolute d-lg-block d-none ms-3 translate-middle-y start-0 top-50">
              search
            </span>

            <span class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 search-close">
              close
            </span>

            <!-- POPUP SEARCH -->
            <div class="search-popup p-3">
              <div class="card rounded-4 overflow-hidden">
                <div class="card-header d-lg-none">
                  <input
                    class="form-control rounded-5 px-5 mobile-search-control"
                    type="text"
                    placeholder="Search..."
                    onkeydown="if(event.key==='Enter'){
  window.location='<?= BASE_URL ?>/?c=search&m=index&q='+encodeURIComponent(this.value)
}">
                </div>
                <div class="card-body text-center text-muted">
                  Ketik lalu tekan <b>ENTER</b> untuk mencari
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- ================= RIGHT MENU ================= -->
        <ul class="navbar-nav gap-1 nav-right-links align-items-center">

          <li class="nav-item d-lg-none mobile-search-btn">
            <a class="nav-link" href="javascript:;">
              <i class="material-icons-outlined">search</i>
            </a>
          </li>

          <li class="nav-item dropdown">
            <a href="javascript:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
              <img src="<?= $photo ?>" class="rounded-circle p-1 border" width="45" height="45" alt="User">
            </a>

            <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">

              <div class="dropdown-item text-center">
                <img src="<?= $photo ?>" class="rounded-circle p-1 shadow mb-2" width="80" height="80">
                <h6 class="mb-0"><?= htmlspecialchars($name) ?></h6>
                <?php if ($email): ?>
                  <small class="text-muted"><?= htmlspecialchars($email) ?></small>
                <?php endif; ?>
              </div>

              <hr class="dropdown-divider">

              <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                href="<?= BASE_URL ?>/?c=admin&m=profile">
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