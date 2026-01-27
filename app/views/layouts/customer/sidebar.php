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
        <a href="<?= BASE_URL ?>/?c=customerOrder&m=index">
          <div class="parent-icon"><i class="material-icons-outlined">receipt_long</i>
          </div>
          <div class="menu-title">History Order</div>
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
        <a href="<?= BASE_URL ?>/?c=sellerCategory&m=index">
          <div class="parent-icon"><i class="material-icons-outlined">list_alt</i>
          </div>
          <div class="menu-title">Reports</div>
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/?c=sellerCategory&m=index">
          <div class="parent-icon"><i class="material-icons-outlined">chat</i>
          </div>
          <div class="menu-title">Chat</div>
        </a>
      </li>
      



      <li class="menu-label">Others</li>
      <li>
        <a href="faq.html">
          <div class="parent-icon"><i class="material-icons-outlined">help_outline</i>
          </div>
          <div class="menu-title">FAQ</div>
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/?c=auth&m=logout">
          <div class="parent-icon"><i class="material-icons-outlined">power_settings_new</i>
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