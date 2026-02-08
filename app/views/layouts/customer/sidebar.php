<script>
  function confirmLogout() {
    Swal.fire({
      title: 'Logout?',
      text: 'Klik logout untuk keluar',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya',
      cancelButtonText: 'Tidak'
    }).then((res) => {
      if (res.isConfirmed) {
        window.location.href = "<?= BASE_URL ?>/?c=auth&m=logout";
      }
    });
  }
</script>
<!--start sidebar-->
<aside class="sidebar-wrapper" data-simplebar="true">
  <div class="sidebar-header">
    <div class="logo-icon">
      <img src="<?= BASE_URL ?>/assets/images/logo.png" class="logo-img" alt="">
    </div>
    <div class="logo-name flex-grow-1">
      <h5 class="mb-0">
        <span class="text-white">Book</span><span class="text-warning">Star</span>
      </h5>
    </div>

    <div class="sidebar-close">
      <span class="material-icons-outlined">close</span>
    </div>
  </div>
  <div class="sidebar-nav">
    <!--navigation-->
    <ul class="metismenu" id="sidenav">
      <li>
        <a href="<?= BASE_URL ?>/?c=customer&m=dashboard">
          <div class="parent-icon"><i class="material-icons-outlined">home</i>
          </div>
          <div class="menu-title">Dashboard</div>
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/?c=customer&m=order">
          <div class="parent-icon"><i class="material-icons-outlined">receipt_long</i>
          </div>
          <div class="menu-title">Order</div>
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/?c=cart&m=index">
          <div class="parent-icon"><i class="material-icons-outlined">shopping_cart</i>
          </div>
          <div class="menu-title">Cart</div>
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/?c=customerOrder&m=index">
          <div class="parent-icon"><i class="material-icons-outlined">receipt_long</i>
          </div>
          <div class="menu-title">Order Status</div>
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/?c=customerReport&m=index">
          <div class="parent-icon">
            <i class="material-icons-outlined">bar_chart</i>
          </div>
          <div class="menu-title">Reports</div>
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/?c=customerChat&m=index">
          <div class="parent-icon"><i class="material-icons-outlined">chat</i>
          </div>
          <div class="menu-title">Chat</div>
        </a>
      </li>




      <li class="menu-label">Others</li>
      <li>
        <a href="<?= BASE_URL ?>/?c=customer&m=faq">
          <div class="parent-icon"><i class="material-icons-outlined">help_outline</i>
          </div>
          <div class="menu-title">FAQ</div>
        </a>
      </li>
      <li>
        <a href="javascript:void(0)" onclick="confirmLogout()">
          <div class="parent-icon">
            <i class="material-icons-outlined">power_settings_new</i>
          </div>
          <div class="menu-title">Sign Out</div>
        </a>
      </li>
      </li>
    </ul>
    <!--end navigation-->
  </div>
</aside>
<!--end sidebar-->