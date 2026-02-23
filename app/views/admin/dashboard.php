<?php require APP_PATH . '/views/layouts/admin/header.php'; ?>
<?php require APP_PATH . '/views/layouts/admin/sidebar.php'; ?>

<main class="main-wrapper">
  <div class="main-content">

    <!-- ALERTS -->
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

    <!-- WELCOME CARD -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
              <img src="<?= htmlspecialchars($photo) ?>"
                class="rounded-circle me-3"
                width="60" height="60"
                alt="user">
              <div>
                <p class="text mb-1">Welcome back</p>
                <h4 class="fw-bold mb-0"><?= htmlspecialchars($name) ?>!</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="mb-3">
                  <h5 class="fw-bold text-success mb-1">
                    Rp <?= number_format(($data['totalRevenue'] ?? 0) / 1000, 0, ',', '.') ?>K
                    <i class="fas fa-arrow-up text-success ms-1"></i>
                  </h5>
                  <p class="text mb-2">Total Revenue</p>
                  <div class="progress" style="height: 5px;">
                    <div class="progress-bar bg-success" style="width: 60%"></div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="mb-3">
                  <h5 class="fw-bold text-danger mb-1">
                    <?= ($data['totalOrders'] ?? 0) ?> <i class="fas fa-shopping-cart text-danger ms-1"></i>
                  </h5>
                  <p class="text mb-2">Total Orders</p>
                  <div class="progress" style="height: 5px;">
                    <div class="progress-bar bg-danger" style="width: 40%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 text-center">
            <img src="<?= BASE_URL ?>/assets/images/gallery/welcome-back-3.png"
              class="img-fluid"
              alt="Welcome"
              style="max-height: 150px;">
          </div>
        </div>
      </div>
    </div>

    <!-- STATISTICS CARDS -->
    <div class="row g-3 mb-4">
      <?php
      // Data dari controller sudah dalam array $data
      // Perhatikan nama key sesuai dengan return dari getDashboardStats()
      $cards = [
        ['Total Users', $data['totalUsers'] ?? 0, 'primary', 'fas fa-users'],
        ['Sellers', $data['totalSellers'] ?? 0, 'success', 'fas fa-store'],
        ['Customers', $data['totalCustomers'] ?? 0, 'info', 'fas fa-user-tag'],
        ['Products', $data['totalProducts'] ?? 0, 'warning', 'fas fa-book'],
        ['Orders', $data['totalOrders'] ?? 0, 'danger', 'fas fa-shopping-cart']
      ];
      ?>
      <?php foreach ($cards as $card): ?>
        <div class="col-6 col-md-4 col-lg-2">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3">
              <div class="icon-wrapper mb-3">
                <div class="bg-soft-<?= $card[2] ?> rounded-circle d-inline-flex align-items-center justify-content-center"
                  style="width: 50px; height: 50px;">
                  <i class="<?= $card[3] ?> text-<?= $card[2] ?> fs-4"></i>
                </div>
              </div>
              <h3 class="fw-bold mb-1"><?= $card[1] ?></h3>
              <p class="text small mb-0"><?= $card[0] ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- CHARTS SECTION -->
    <div class="row g-3 mb-4">
      <!-- ORDER CHART -->
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div>
                <h5 class="fw-bold mb-1">Order Terbaru</h5>
                <p class="text small">5 order terakhir dalam sistem</p>
              </div>
            </div>

            <?php if (!empty($data['recentOrders'])): ?>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr class="border-bottom">
                      <th class="text small fw-normal">#Order</th>
                      <th class="text small fw-normal">Customer</th>
                      <th class="text small fw-normal">Total</th>
                      <th class="text small fw-normal">Status</th>
                      <th class="text small fw-normal">Tanggal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($data['recentOrders'] as $order): ?>
                      <tr>
                        <td>
                          <span class="fw-bold text-primary">#<?= htmlspecialchars($order['order_code'] ?? 'N/A') ?></span>
                        </td>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2"
                              style="width: 30px; height: 30px;">
                              <?= strtoupper(substr(($order['customer_name'] ?? $order['customer'] ?? 'Customer'), 0, 1)) ?>
                            </div>
                            <span><?= htmlspecialchars($order['customer_name'] ?? $order['customer'] ?? 'Customer') ?></span>
                          </div>
                        </td>
                        <td class="fw-bold">
                          Rp <?= number_format($order['total_price'] ?? 0, 0, ',', '.') ?>
                        </td>
                        <td>
                          <?php
                          $status = $order['status'] ?? $order['approval_status'] ?? 'pending';
                          $statusConfig = [
                            'approved' => ['color' => 'success', 'icon' => 'check-circle'],
                            'pending' => ['color' => 'warning', 'icon' => 'clock'],
                            'rejected' => ['color' => 'danger', 'icon' => 'times-circle'],
                            'completed' => ['color' => 'success', 'icon' => 'check-circle'],
                            'processing' => ['color' => 'info', 'icon' => 'sync']
                          ];
                          $config = $statusConfig[strtolower($status)] ?? ['color' => 'secondary', 'icon' => 'circle'];
                          ?>
                          <span class="badge bg-soft-<?= $config['color'] ?> text-<?= $config['color'] ?>">
                            <i class="fas fa-<?= $config['icon'] ?> me-1"></i>
                            <?= ucfirst($status) ?>
                          </span>
                        </td>
                        <td class="text">
                          <?= date('d M Y', strtotime($order['created_at'] ?? date('Y-m-d'))) ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php else: ?>
              <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text mb-3"></i>
                <p class="text">Belum ada order</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- USER DISTRIBUTION -->
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="mb-4">
              <h5 class="fw-bold mb-1">Distribusi User</h5>
              <p class="text small">Komposisi pengguna sistem</p>
            </div>
            <div id="userChart" style="min-height: 300px;"></div>
            <div class="mt-4">
              <div class="row g-2">
                <div class="col-4 text-center">
                  <div class="p-2">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                      style="width: 30px; height: 30px;">
                      <i class="fas fa-user-cog text-white fs-6"></i>
                    </div>
                    <p class="mb-0 small">Admin</p>
                  </div>
                </div>
                <div class="col-4 text-center">
                  <div class="p-2">
                    <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                      style="width: 30px; height: 30px;">
                      <i class="fas fa-store text-white fs-6"></i>
                    </div>
                    <p class="mb-0 small">Seller</p>
                  </div>
                </div>
                <div class="col-4 text-center">
                  <div class="p-2">
                    <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                      style="width: 30px; height: 30px;">
                      <i class="fas fa-user text-white fs-6"></i>
                    </div>
                    <p class="mb-0 small">Customer</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


  </div>
</main>

<?php require APP_PATH . '/views/layouts/admin/footer.php'; ?>

<!-- STYLES -->
<style>
  .search-box {
    position: relative;
    width: 250px;
  }

  .search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
  }

  .search-box .form-control {
    padding-left: 40px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background-color: white;
    height: 40px;
  }

  .search-box .form-control:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
  }

  .bg-soft-primary {
    background-color: rgba(67, 97, 238, 0.1) !important;
  }

  .bg-soft-success {
    background-color: rgba(6, 214, 160, 0.1) !important;
  }

  .bg-soft-info {
    background-color: rgba(17, 138, 178, 0.1) !important;
  }

  .bg-soft-warning {
    background-color: rgba(255, 209, 102, 0.1) !important;
  }

  .bg-soft-danger {
    background-color: rgba(239, 71, 111, 0.1) !important;
  }

  .bg-soft-purple {
    background-color: rgba(111, 66, 193, 0.1) !important;
  }

  .card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  }

  .card:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  }

  .badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 500;
  }

  .list-group-item {
    border-left: 0;
    border-right: 0;
  }

  .list-group-item:first-child {
    border-top: 0;
  }

  .list-group-item:last-child {
    border-bottom: 0;
  }
</style>

<!-- DATA FOR CHARTS -->
<script>
  // Data dari controller - sesuaikan dengan struktur return
  <?php
  // Data untuk chart order per bulan
  $ordersChart = isset($data['ordersChart']) && is_array($data['ordersChart']) ? $data['ordersChart'] : [];

  // Format data untuk chart
  if (!empty($ordersChart) && is_array($ordersChart)) {
    $orderMonths = [];
    $orderCounts = [];

    foreach ($ordersChart as $item) {
      // Asumsikan struktur: ['month' => 'Jan', 'count' => 10]
      if (isset($item['month'])) {
        $orderMonths[] = $item['month'];
      }
      if (isset($item['count'])) {
        $orderCounts[] = $item['count'];
      }
    }

    // Jika array kosong, beri default
    if (empty($orderMonths)) {
      $orderMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
      $orderCounts = [10, 25, 30, 45, 35, 50];
    }
  } else {
    $orderMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $orderCounts = [10, 25, 30, 45, 35, 50];
  }

  // Data untuk user distribution
  $userStats = isset($data['userStats']) && (is_array($data['userStats']) || is_object($data['userStats']))
    ? $data['userStats']
    : ['Admin' => 1, 'Seller' => 5, 'Customer' => 45];
  ?>

  const orderMonths = <?= json_encode($orderMonths) ?>;
  const orderTotals = <?= json_encode($orderCounts) ?>;
  const userStatsData = <?= json_encode($userStats) ?>;

  // Debug log
  console.log('Chart Data - Months:', orderMonths);
  console.log('Chart Data - Totals:', orderTotals);
  console.log('User Stats:', userStatsData);
</script>

<!-- CHARTS SCRIPTS -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Order Chart
    if (document.querySelector("#orderChart")) {
      const orderChart = new ApexCharts(document.querySelector("#orderChart"), {
        chart: {
          type: 'line',
          height: 300,
          toolbar: {
            show: false
          }
        },
        series: [{
          name: 'Total Order',
          data: orderTotals
        }],
        xaxis: {
          categories: orderMonths,
          labels: {
            style: {
              colors: '#64748b'
            }
          }
        },
        yaxis: {
          labels: {
            style: {
              colors: '#64748b'
            }
          }
        },
        stroke: {
          curve: 'smooth',
          width: 3,
          colors: ['#4361ee']
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.6,
            opacityTo: 0.1,
            stops: [0, 90, 100]
          }
        },
        grid: {
          borderColor: '#f1f5f9',
          strokeDashArray: 5
        },
        dataLabels: {
          enabled: false
        },
        colors: ['#4361ee']
      });
      orderChart.render();
    }

    // User Chart
    if (document.querySelector("#userChart")) {
      const userChart = new ApexCharts(document.querySelector("#userChart"), {
        chart: {
          type: 'donut',
          height: 300
        },
        series: Object.values(userStatsData),
        labels: Object.keys(userStatsData),
        colors: ['#4361ee', '#06d6a0', '#ffd166'],
        legend: {
          position: 'bottom',
          labels: {
            colors: '#64748b'
          }
        },
        plotOptions: {
          pie: {
            donut: {
              size: '70%',
              labels: {
                show: true,
                name: {
                  show: true,
                  fontSize: '14px'
                },
                value: {
                  show: true,
                  fontSize: '20px',
                  fontWeight: 'bold'
                },
                total: {
                  show: true,
                  label: 'Total Users',
                  fontSize: '14px'
                }
              }
            }
          }
        },
        dataLabels: {
          enabled: false
        }
      });
      userChart.render();
    }
  });
</script>