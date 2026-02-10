<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<?php
$user  = $_SESSION['user'] ?? [];
$name  = $user['name'] ?? 'User';
$photo = !empty($user['photo'])
  ? BASE_URL . '/uploads/profile/' . $user['photo']
  : BASE_URL . '/assets/images/default-avatar.png';

$monthlySales = $monthlySales ?? [];
$recentProducts = $recentProducts ?? [];
$orderStatus    = $orderStatus ?? ['approved' => 0, 'pending' => 0, 'rejected' => 0];
$todaySales     = $todaySales ?? 0;

// Calculate growth rate
$totalOrder = array_sum($orderStatus);
$growth = ($totalOrder > 0 && isset($orderStatus['approved'])) 
  ? round(($orderStatus['approved'] / $totalOrder) * 100)
  : 0;
?>

<style>
/* Dark Blue Theme */
:root {
    --bg-primary: #0b1229;        /* biru gelap utama (background body) */
--bg-secondary: #111a3a;      /* biru gelap sedikit naik */
--bg-card: #151f45;           /* card biru gelap */
--bg-card-light: #1f2a5c;     /* hover / active card */
--bg-card-dark: #0b1229;      /* card paling gelap */
      /* Darker blue for contrast */
    
    /* Text Colors */
    --text-primary: #f1f5f9;        /* Very light blue/gray */
    --text-secondary: #cbd5e1;      /* Light gray/blue */
    --text-muted: #94a3b8;          /* Muted blue/gray */
    
    /* Accent Colors */
    --primary: #60a5fa;             /* Bright blue */
    --primary-light: #93c5fd;
    --primary-dark: #3b82f6;
    --success: #34d399;             /* Green */
    --warning: #fbbf24;             /* Yellow */
    --danger: #f87171;              /* Red */
    --info: #38bdf8;               /* Light blue */
    
    /* Borders */
    --border-color: #334155;        /* Blue-gray border */
    --border-light: #475569;        /* Lighter border */
    
    /* Shadows */
    --shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.4);
    --shadow-hover: 0 20px 25px rgba(0, 0, 0, 0.5);
}

body {
    background: var(--bg-primary);
    color: var(--text-primary);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Dashboard Cards - Dark Blue */
.dashboard-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
}

.dashboard-card:hover {
    box-shadow: var(--shadow-hover);
    border-color: var(--border-light);
    transform: translateY(-2px);
    background: var(--bg-card-light);
}

/* Stats Cards with gradient */
.stats-card {
    padding: 24px;
    border-radius: 14px;
    background: linear-gradient(145deg, var(--bg-card), var(--bg-card-dark));
    border: 1px solid var(--border-color);
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(to bottom, var(--primary), var(--info));
}

.icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(96, 165, 250, 0.15);
    color: var(--primary);
    border: 1px solid rgba(96, 165, 250, 0.3);
}

.icon-wrapper.success { 
    background: rgba(52, 211, 153, 0.15);
    color: var(--success);
    border-color: rgba(52, 211, 153, 0.3);
}
.icon-wrapper.warning { 
    background: rgba(251, 191, 36, 0.15);
    color: var(--warning);
    border-color: rgba(251, 191, 36, 0.3);
}
.icon-wrapper.danger { 
    background: rgba(248, 113, 113, 0.15);
    color: var(--danger);
    border-color: rgba(248, 113, 113, 0.3);
}

.progress-thin {
    height: 6px;
    border-radius: 3px;
    background: rgba(255, 255, 255, 0.1);
}

.progress-bar {
    background: linear-gradient(to right, var(--primary), var(--info));
    border-radius: 3px;
}

/* Product Cards */
.product-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    overflow: hidden;
    transition: all 0.4s ease;
    height: 100%;
}

.product-card:hover {
    border-color: var(--primary);
    transform: translateY(-4px);
    box-shadow: var(--shadow-hover);
}

/* Buttons */
.btn-outline-primary {
    border: 2px solid var(--primary);
    color: var(--primary);
    background: transparent;
    border-radius: 10px;
    padding: 8px 16px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: var(--primary);
    color: var(--text-primary);
    border-color: var(--primary);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border: none;
    color: var(--text-primary);
    border-radius: 10px;
    padding: 8px 16px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark), #2563eb);
    color: var(--text-primary);
}

/* Welcome Header */
.welcome-header {
    background: linear-gradient(135deg, var(--bg-card), var(--bg-primary));
    border: 1px solid var(--border-color);
    border-radius: 18px;
    position: relative;
    overflow: hidden;
}

.welcome-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(to right, var(--primary), var(--info));
}

/* Order Status Items */
.order-status-item {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 12px;
    border-left: 4px solid;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.order-status-item:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--border-light);
}

.order-status-item.approved { 
    border-left-color: var(--success);
}
.order-status-item.pending { 
    border-left-color: var(--warning);
}
.order-status-item.rejected { 
    border-left-color: var(--danger);
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 20px;
    border: 1px solid transparent;
}

.bg-success { 
    background: rgba(52, 211, 153, 0.2) !important;
    color: var(--success) !important;
    border-color: rgba(52, 211, 153, 0.3);
}
.bg-danger { 
    background: rgba(248, 113, 113, 0.2) !important;
    color: var(--danger) !important;
    border-color: rgba(248, 113, 113, 0.3);
}
.bg-primary { 
    background: rgba(96, 165, 250, 0.2) !important;
    color: var(--primary) !important;
    border-color: rgba(96, 165, 250, 0.3);
}

/* Text Colors */
.text-primary { color: var(--text-primary) !important; }
.text-secondary { color: var(--text-secondary) !important; }
.text-muted { color: var(--text-muted) !important; }
.text-success { color: var(--success) !important; }
.text-danger { color: var(--danger) !important; }
.text-warning { color: var(--warning) !important; }

/* Dropdown */
.dropdown-menu {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-lg);
    border-radius: 12px;
    padding: 8px;
}

.dropdown-item {
    border-radius: 8px;
    padding: 8px 12px;
    color: var(--text-primary);
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
}

/* Chart Containers */
.chart-container {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 24px;
    border: 1px solid var(--border-color);
}
</style>

<main class="main-wrapper">
  <div class="container-fluid py-4">
    
    <!-- Welcome Header -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="welcome-header">
          <div class="p-4">
            <div class="row align-items-center">
              <div class="col-md-8">
                <div class="d-flex align-items-center">
                  <div class="position-relative me-4">
                    <div class="rounded-circle border border-3" style="border-color: var(--primary) !important; padding: 2px;">
                      <img src="<?= htmlspecialchars($photo) ?>"
                           class="rounded-circle"
                           width="70" height="70">
                    </div>
                    <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1 border border-2" style="border-color: var(--bg-card) !important;"></span>
                  </div>
                  <div>
                    <p class="text-muted mb-1">Welcome back, Seller</p>
                    <h2 class="fw-bold mb-1"><?= htmlspecialchars($name) ?></h2>
                    <p class="text-muted mb-0">Here's what's happening with your store today</p>
                  </div>
                </div>
              </div>
              <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-color);">
                  <p class="text-muted mb-1">Today's Sales</p>
                  <h3 class="fw-bold mb-0 text-primary">Rp <?= number_format($todaySales, 0, ',', '.') ?></h3>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
<div class="row g-4 mb-4">
  <div class="col-xl-3 col-md-6">
    <div class="stats-card">
      <div class="d-flex align-items-center">
        <div class="icon-wrapper me-3">
          <i class="bi bi-wallet2 fs-4"></i> <!-- Changed to Bootstrap Icon -->
        </div>
        <div class="flex-grow-1">
          <p class="text-muted mb-1">Total Sales</p>
          <h3 class="fw-bold mb-0 text-primary">Rp <?= number_format($todaySales, 0, ',', '.') ?></h3>
        </div>
      </div>
      <div class="progress progress-thin mt-3">
        <div class="progress-bar" style="width: 60%"></div>
      </div>
    </div>
  </div>
  
  <div class="col-xl-3 col-md-6">
    <div class="stats-card">
      <div class="d-flex align-items-center">
        <div class="icon-wrapper success me-3">
          <i class="bi bi-graph-up-arrow fs-4"></i> <!-- Changed to Bootstrap Icon -->
        </div>
        <div class="flex-grow-1">
          <p class="text-muted mb-1">Growth Rate</p>
          <h3 class="fw-bold mb-0 text-success"><?= $growth ?>%</h3>
        </div>
      </div>
      <div class="progress progress-thin mt-3">
        <div class="progress-bar bg-success" style="width: <?= $growth ?>%"></div>
      </div>
    </div>
  </div>
  
  <div class="col-xl-3 col-md-6">
    <div class="stats-card">
      <div class="d-flex align-items-center">
        <div class="icon-wrapper warning me-3">
          <i class="bi bi-cart fs-4"></i> <!-- Changed to Bootstrap Icon -->
        </div>
        <div class="flex-grow-1">
          <p class="text-muted mb-1">Total Orders</p>
          <h3 class="fw-bold mb-0 text-warning"><?= $totalOrder ?></h3>
        </div>
      </div>
      <div class="progress progress-thin mt-3">
        <div class="progress-bar bg-warning" style="width: 40%"></div>
      </div>
    </div>
  </div>
  
  <div class="col-xl-3 col-md-6">
    <div class="stats-card">
      <div class="d-flex align-items-center">
        <div class="icon-wrapper success me-3">
          <i class="bi bi-check-circle fs-4"></i> <!-- Changed to Bootstrap Icon -->
        </div>
        <div class="flex-grow-1">
          <p class="text-muted mb-1">Approved</p>
          <h3 class="fw-bold mb-0 text-success"><?= $orderStatus['approved'] ?? 0 ?></h3>
        </div>
      </div>
      <div class="progress progress-thin mt-3">
        <div class="progress-bar bg-success" style="width: 70%"></div>
      </div>
    </div>
  </div>
</div>

    <!-- Charts and Order Status -->
    <div class="row g-4 mb-4">
      <!-- Sales Chart -->
      <div class="col-lg-8">
        <div class="chart-container">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h5 class="fw-bold mb-1">Sales Overview</h5>
              <p class="text-muted mb-0 small">Monthly revenue performance</p>
            </div>
            <div class="dropdown">
              <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" 
                      data-bs-toggle="dropdown">
                This Year
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">Last Year</a></li>
              </ul>
            </div>
          </div>
          <div id="salesChart" style="min-height: 320px;"></div>
        </div>
      </div>
      
      <!-- Order Status -->
      <div class="col-lg-4">
        <div class="chart-container h-100">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h5 class="fw-bold mb-1">Order Status</h5>
              <p class="text-muted mb-0 small">Current order distribution</p>
            </div>
            <i class="ti ti-chart-pie text-primary"></i>
          </div>
          
          <div class="text-center mb-4">
            <div id="orderChart" style="min-height: 220px;"></div>
          </div>
          
          <div class="mt-4">
            <div class="order-status-item approved">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-success me-3" style="width: 12px; height: 12px;"></div>
                  <span class="fw-medium">Approved</span>
                </div>
                <div>
                  <span class="fw-bold fs-5 text-success"><?= $orderStatus['approved'] ?? 0 ?></span>
                  <small class="text-muted ms-1">orders</small>
                </div>
              </div>
            </div>
            
            <div class="order-status-item pending">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-warning me-3" style="width: 12px; height: 12px;"></div>
                  <span class="fw-medium">Pending</span>
                </div>
                <div>
                  <span class="fw-bold fs-5 text-warning"><?= $orderStatus['pending'] ?? 0 ?></span>
                  <small class="text-muted ms-1">orders</small>
                </div>
              </div>
            </div>
            
            <div class="order-status-item rejected">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-danger me-3" style="width: 12px; height: 12px;"></div>
                  <span class="fw-medium">Rejected</span>
                </div>
                <div>
                  <span class="fw-bold fs-5 text-danger"><?= $orderStatus['rejected'] ?? 0 ?></span>
                  <small class="text-muted ms-1">orders</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Products -->
    <div class="row">
      <div class="col-12">
        <div class="chart-container">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h5 class="fw-bold mb-1">Recent Products</h5>
              <p class="text-muted mb-0 small">Latest added products to your store</p>
            </div>
            <a href="<?= BASE_URL ?>/?c=sellerProduct&m=index" class="btn btn-outline-primary btn-sm">View All</a>
          </div>
          
          <?php if (empty($recentProducts)): ?>
            <div class="text-center py-5">
              <div class="mb-3">
                <div class="mx-auto" style="width: 64px; height: 64px; border-radius: 16px; background: rgba(96, 165, 250, 0.15); display: flex; align-items: center; justify-content: center;">
                  <i class="ti ti-package text-primary fs-2"></i>
                </div>
              </div>
              <h6 class="mb-2">No products yet</h6>
              <p class="text-muted mb-3">Start adding products to your store</p>
              <a href="#" class="btn btn-primary">Add Product</a>
            </div>
          <?php else: ?>
            <div class="row g-4">
              <?php foreach ($recentProducts as $p): ?>
                <div class="col-xl-3 col-lg-4 col-md-6">
                  <div class="product-card">
                    <div class="position-relative">
                      <img src="<?= BASE_URL ?>/uploads/products/<?= $p['image'] ?>"
                           class="img-fluid w-100"
                           style="height: 180px; object-fit: cover; border-radius: 12px 12px 0 0;"
                           onerror="this.src='<?= BASE_URL ?>/assets/images/default-product.jpg'">
                      <?php if (($p['stock'] ?? 0) <= 5): ?>
                        <span class="badge bg-danger position-absolute top-3 start-3">Low Stock</span>
                      <?php else: ?>
                        <span class="badge bg-success position-absolute top-3 start-3">Stock <?= $p['stock'] ?? 0 ?></span>
                      <?php endif; ?>
                    </div>
                    <div class="p-4">
                      <h6 class="fw-bold mb-1"><?= htmlspecialchars($p['name'] ?? 'No Name') ?></h6>
                      
                      
                      <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold fs-5 text-primary">
                          Rp <?= number_format($p['price'] ?? 0, 0, ',', '.') ?>
                        </span>
                      </div>
                      
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>
</main>

<?php
// Prepare chart data
$salesData = array_fill(0, 12, 0);
foreach ($monthlySales as $row) {
    $index = (int)$row['bulan'] - 1;
    $salesData[$index] = (int)$row['total'];
}

$currentMonth = (int)date('n');
$salesData = array_slice($salesData, 0, $currentMonth);

$bulanLabel = array_slice(
    ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
    0,
    $currentMonth
);
?>

<!-- ApexCharts -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.css">
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0"></script>

<script>
// Dark Theme for ApexCharts
const darkTheme = {
    mode: 'dark',
    palette: 'palette1'
};

// Sales Chart with dark theme
var salesOptions = {
    series: [{
        name: 'Sales Revenue',
        data: <?= json_encode($salesData) ?>
    }],
    chart: {
        type: 'area',
        height: 320,
        toolbar: {
            show: true,
            tools: {
                download: true,
                selection: false,
                zoom: false,
                zoomin: false,
                zoomout: false,
                pan: false,
                reset: false
            }
        },
        foreColor: '#94a3b8',
        background: 'transparent'
    },
    colors: ['#60a5fa'],
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.4,
            opacityTo: 0.1,
            stops: [0, 90, 100]
        }
    },
    stroke: {
        curve: 'smooth',
        width: 3,
        colors: ['#60a5fa']
    },
    dataLabels: {
        enabled: false
    },
    xaxis: {
        categories: <?= json_encode($bulanLabel) ?>,
        labels: {
            style: {
                colors: '#94a3b8',
                fontSize: '13px'
            }
        },
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false
        }
    },
    yaxis: {
        labels: {
            formatter: function(val) {
                return 'Rp ' + val.toLocaleString('id-ID');
            },
            style: {
                colors: '#94a3b8',
                fontSize: '13px'
            }
        }
    },
    grid: {
        borderColor: '#334155',
        strokeDashArray: 4,
        yaxis: {
            lines: {
                show: true
            }
        },
        xaxis: {
            lines: {
                show: true
            }
        }
    },
    tooltip: {
        theme: 'dark',
        style: {
            fontSize: '13px',
            fontFamily: 'inherit'
        },
        y: {
            formatter: function(val) {
                return 'Rp ' + val.toLocaleString('id-ID');
            }
        }
    }
};

var salesChart = new ApexCharts(document.querySelector("#salesChart"), salesOptions);
salesChart.render();

// Order Status Chart with dark theme
var orderOptions = {
    series: [<?= $orderStatus['approved'] ?? 0 ?>, <?= $orderStatus['pending'] ?? 0 ?>, <?= $orderStatus['rejected'] ?? 0 ?>],
    chart: {
        type: 'donut',
        height: 220,
        foreColor: '#94a3b8',
        background: 'transparent'
    },
    colors: ['#34d399', '#fbbf24', '#f87171'],
    labels: ['Approved', 'Pending', 'Rejected'],
    plotOptions: {
        pie: {
            donut: {
                size: '65%',
                labels: {
                    show: true,
                    name: {
                        color: '#94a3b8',
                        fontSize: '13px',
                        fontFamily: 'inherit'
                    },
                    value: {
                        color: '#f1f5f9',
                        fontSize: '24px',
                        fontWeight: 'bold',
                        fontFamily: 'inherit'
                    },
                    total: {
                        show: true,
                        label: 'Total Orders',
                        color: '#94a3b8',
                        fontSize: '13px',
                        fontFamily: 'inherit',
                        formatter: function(w) {
                            return <?= $totalOrder ?>;
                        }
                    }
                }
            }
        }
    },
    dataLabels: {
        enabled: false
    },
    legend: {
        show: false
    },
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                height: 180
            }
        }
    }]
};

var orderChart = new ApexCharts(document.querySelector("#orderChart"), orderOptions);
orderChart.render();
</script>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>