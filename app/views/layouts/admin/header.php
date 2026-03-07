<?php
$user = $_SESSION['user'] ?? null;

$photo = (!empty($user['photo']))
  ? BASE_URL . '/uploads/profile/' . $user['photo']
  : 'https://placehold.co/110x110/png';

$name  = $user['name'] ?? 'Guest';
$email = $user['email'] ?? '';

// ---------------------
// Pemetaan halaman yang punya search
// ---------------------
$searchPages = [
  'adminCategory' => 'index',
  'adminProduct'  => 'index',
  'adminUser'     => 'index',
  'admin'         => ['seller', 'customer'] // controller admin, method seller & customer
];

// ---------------------
// Ambil controller & method sekarang
// ---------------------
// Jika $_GET['c'] tidak ada, pakai default page (misal adminCategory/index)
$curController = $_GET['c'] ?? 'adminCategory';
$curMethod     = $_GET['m'] ?? 'index';

// ---------------------
// Cek apakah search aktif di halaman ini
// ---------------------
$showSearch = false;
$searchAction = '';

if (isset($searchPages[$curController])) {
  if (is_array($searchPages[$curController])) {
    if (in_array($curMethod, $searchPages[$curController])) {
      $showSearch = true;
    }
  } else {
    if ($curMethod == $searchPages[$curController]) {
      $showSearch = true;
    }
  }
}

// ---------------------
// Action search selalu pasti ke controller & method saat ini
// ---------------------
if ($showSearch) {
  $searchAction = BASE_URL . "/?c=$curController&m=$curMethod";
}
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link href="<?= BASE_URL ?>/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/plugins/metismenu/metisMenu.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/plugins/metismenu/mm-vertical.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet">

  <link href="<?= BASE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        <!-- HAMBURGER -->
        <div class="btn-toggle">
          <a href="javascript:;"><i class="material-icons-outlined">menu</i></a>
        </div>

        <!-- ================= SEARCH BAR ================= -->
        <?php if ($showSearch): ?>
          <div class="flex-grow-1 ms-3">
            <form method="GET" action="<?= $searchAction ?>" class="d-flex gap-2">
              <input type="hidden" name="c" value="<?= $curController ?>">
              <input type="hidden" name="m" value="<?= $curMethod ?>">
              <input type="text" name="q" class="form-control" placeholder="Search..."
                value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
              <button type="submit" class="btn btn-primary">Search</button>
              <?php if (!empty($_GET['q'])): ?>
                <a href="<?= $searchAction ?>" class="btn btn-secondary">Reset</a>
              <?php endif; ?>
            </form>
          </div>
        <?php endif; ?>

        <!-- ================= RIGHT MENU ================= -->
        <ul class="navbar-nav ms-auto align-items-center">
          
          <!-- NOTIFICATIONS DROPDOWN (TAMBAHKAN INI) -->
          <li class="nav-item dropdown">
            <div class="dropdown-menu dropdown-notify dropdown-menu-end shadow">
              
              <div class="notify-list"> 
              </div>
            </div>
          </li>

          <!-- USER DROPDOWN (HANYA SATU) -->
          <li class="nav-item dropdown">
            <a href="javascript:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
              <img src="<?= $photo ?>" class="rounded-circle p-1 border" width="45" height="45" alt="User">
            </a>
            <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
              <div class="dropdown-item text-center">
                <img src="<?= $photo ?>" class="rounded-circle p-1 shadow mb-2" width="80" height="80">
                <h6 class="mb-0"><?= htmlspecialchars($name) ?></h6>
                <?php if ($email): ?>
                  <small class="text"><?= htmlspecialchars($email) ?></small>
                <?php endif; ?>
              </div>
              <hr class="dropdown-divider">
              <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= BASE_URL ?>/?c=admin&m=profile">
                <i class="material-icons-outlined">person_outline</i> Profile
              </a>
              <hr class="dropdown-divider">
              <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= BASE_URL ?>/?c=auth&m=logout">
                <i class="material-icons-outlined">power_settings_new</i> Logout
              </a>
            </div>
          </li>
        </ul>

      </nav>
    </header>
    