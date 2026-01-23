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
        <a href="<?= BASE_URL ?>/?c=seller&m=dashboard">
          <div class="parent-icon"><i class="material-icons-outlined">home</i>
          </div>
          <div class="menu-title">Dashboard</div>
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/?c=seller&m=seller">
          <div class="parent-icon"><i class="material-icons-outlined">person</i>
          </div>
          <div class="menu-title">Seller</div>
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/?c=seller&m=customer">
          <div class="parent-icon"><i class="material-icons-outlined">groups</i>
          </div>
          <div class="menu-title">Customer</div>
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/?c=sellerCategory&m=index">
          <div class="parent-icon"><i class="material-icons-outlined">category</i>
          </div>
          <div class="menu-title">Manage Category</div>
        </a>
      </li>
      <li>
       <li>
          <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="material-icons-outlined">shopping_bag</i>
            </div>
            <div class="menu-title">eCommerce</div>
          </a>
          <ul>
            <li><a href="<?= BASE_URL ?>/?c=sellerProduct&m=create"><i class="material-icons-outlined">arrow_right</i>Add Product</a>
            </li>
            <li><a href="<?= BASE_URL ?>/?c=sellerProduct&m=index"><i class="material-icons-outlined">arrow_right</i>Products</a>
            </li>
            <li><a href="ecommerce-customers.html"><i class="material-icons-outlined">arrow_right</i>Customers</a>
            </li>
            <li><a href="ecommerce-customer-details.html"><i class="material-icons-outlined">arrow_right</i>Customer Details</a>
            </li>
            <li><a href="ecommerce-orders.html"><i class="material-icons-outlined">arrow_right</i>Orders</a>
            </li>
            <li><a href="ecommerce-order-details.html"><i class="material-icons-outlined">arrow_right</i>Order Details</a>
            </li>
          </ul>     
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