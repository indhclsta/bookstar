<?php require APP_PATH . '/views/layouts/admin/header.php'; ?>
<?php require APP_PATH . '/views/layouts/admin/sidebar.php'; ?>

<main class="main-wrapper">
  <div class="main-content">

    <!-- PAGE TITLE -->
    <div class="mb-4">
      <h4 class="fw-bold">📊 Admin Dashboard</h4>
      <p class="text-muted">Ringkasan sistem BookStar</p>
      <?php
$user = $_SESSION['user'] ?? [];

$name = $user['name'] ?? 'User';

$photo = !empty($user['photo'])
  ? BASE_URL . '/uploads/profile/' . $user['photo']
  : 'https://placehold.co/110x110/png';
?>
<?php if (!empty($_SESSION['success'])): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: '<?= $_SESSION['success']; ?>',
      timer: 2500,
      timerProgressBar: true,
      showConfirmButton: false
    });
  </script>
<?php unset($_SESSION['success']);
endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: '<?= $_SESSION['error']; ?>'
    });
  </script>
<?php unset($_SESSION['error']);
endif; ?>

  <div>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

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
    </div>
  
    <!-- STAT CARDS -->
    <div class="row g-3 mb-4">
      <?php
      $cards = [
  ['Total Users', $data['totalUsers'], 'primary'],
  ['Sellers', $data['totalSellers'], 'success'],
  ['Customers', $data['totalCustomers'], 'info'],
  ['Products', $data['totalProducts'], 'warning'],
  ['Orders', $data['totalOrders'], 'danger'],
];
      ?>

      <?php foreach ($cards as $card): ?>
        <div class="col-md-4 col-lg-2">
          <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
              <h6 class="text-muted"><?= $card[0] ?></h6>
              <h3 class="fw-bold text-<?= $card[2] ?>">
                <?= $card[1] ?>
              </h3>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- TOTAL REVENUE -->
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h6 class="text-muted mb-1">💰 Total Revenue</h6>
            <h3 class="fw-bold text-success">
              Rp <?= number_format($data['totalRevenue'], 0, ',', '.') ?>

            </h3>
          </div>
        </div>
      </div>
    </div>

    <!-- RECENT ORDERS -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-body">
        <h5 class="fw-semibold mb-3">🧾 Recent Orders</h5>

        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>#Order</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($data['recentOrders'])): ?>
  <?php foreach ($data['recentOrders'] as $order): ?>
    <tr>
      <td>#<?= htmlspecialchars($order['order_code']) ?></td>
      <td><?= htmlspecialchars($order['customer']) ?></td>
      <td>
        Rp <?= number_format($order['total_price'], 0, ',', '.') ?>
      </td>
      <td>
        <span class="badge bg-<?=
          $order['approval_status'] === 'approved' ? 'success' :
          ($order['approval_status'] === 'pending' ? 'warning' : 'danger')
        ?>">
          <?= ucfirst($order['approval_status']) ?>
        </span>
      </td>
      <td>
        <?= date('d M Y', strtotime($order['created_at'])) ?>
      </td>
    </tr>
  <?php endforeach; ?>
<?php else: ?>
  <tr>
    <td colspan="5" class="text-center text-muted">
      Tidak ada data
    </td>
  </tr>
<?php endif; ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- PENDING ORDERS -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-body">
        <h5 class="fw-semibold mb-3">⏳ Pending Orders</h5>

        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead class="table-light">
              <tr>
                <th>#Order</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($data['pendingOrders'])): ?>
  <?php foreach ($data['pendingOrders'] as $order): ?>
    <tr>
      <td>#<?= htmlspecialchars($order['order_code']) ?></td>
      <td><?= htmlspecialchars($order['customer']) ?></td>
      <td>
        Rp <?= number_format($order['total_price'], 0, ',', '.') ?>
      </td>
      <td>
        <?= date('d M Y', strtotime($order['created_at'])) ?>
      </td>
    </tr>
  <?php endforeach; ?>
<?php else: ?>
  <tr>
    <td colspan="4" class="text-center text-muted">
      Tidak ada order pending
    </td>
  </tr>
<?php endif; ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- CHART SECTION -->
    <div class="row g-3 mb-4">

      <!-- ORDER PER BULAN -->
      <div class="col-md-8">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body">
            <h5 class="fw-semibold mb-3">📈 Order per Bulan</h5>
            <div id="orderChart"></div>
          </div>
        </div>
      </div>

      <!-- USER DISTRIBUTION -->
      <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body">
            <h5 class="fw-semibold mb-3">👥 Distribusi User</h5>
            <div id="userChart"></div>
          </div>
        </div>
      </div>

    </div>

  </div>
</main>

<?php require APP_PATH . '/views/layouts/admin/footer.php'; ?>

<!-- PASS DATA PHP KE JS -->
<script>
  const orderMonths = <?= json_encode(array_keys($data['orderMonthly'] ?? [])) ?>;
  const orderTotals = <?= json_encode(array_values($data['orderMonthly'] ?? [])) ?>;
  const userStats = <?= json_encode($data['userStats'] ?? []) ?>;
</script>

<!-- ORDER CHART -->
<script>
  new ApexCharts(document.querySelector("#orderChart"), {
    chart: {
      type: 'line',
      height: 300,
      toolbar: { show: false }
    },
    series: [{
      name: 'Orders',
      data: orderTotals
    }],
    xaxis: {
      categories: orderMonths
    },
    stroke: {
      curve: 'smooth',
      width: 3
    },
    dataLabels: { enabled: false },
    colors: ['#0d6efd']
  }).render();
</script>

<!-- USER CHART -->
<script>
  new ApexCharts(document.querySelector("#userChart"), {
    chart: {
      type: 'donut',
      height: 300
    },
    series: Object.values(userStats),
    labels: Object.keys(userStats),
    legend: { position: 'bottom' },
    colors: ['#198754', '#0dcaf0', '#ffc107']
  }).render();
</script>
