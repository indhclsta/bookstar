<!--bootstrap js - URUTAN YANG BENAR -->
<script src="<?= BASE_URL ?>/assets/js/jquery.min.js"></script>  <!-- 1. JQUERY DULU -->
<script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>  <!-- 2. BOOTSTRAP -->

<!-- PLUGINS -->
<script src="<?= BASE_URL ?>/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/metismenu/metisMenu.min.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/peity/jquery.peity.min.js"></script>

<!-- MAIN SCRIPT -->
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>

<!-- CHART - HANYA DI HALAMAN DASHBOARD -->
<?php 
$currentController = $_GET['c'] ?? '';
$currentMethod = $_GET['m'] ?? '';
if ($currentController == 'seller' && $currentMethod == 'dashboard'): 
?>
    <script src="<?= BASE_URL ?>/assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/dashboard1.js"></script>
    <script src="<?= BASE_URL ?>/assets/plugins/apexchart/apex-custom-chart.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/data-widgets.js"></script>
<?php endif; ?>

<!-- SIMPLE TOGGLE DEBUG (PASTIKAN INI JALAN) -->
<script>
$(document).ready(function() {
    console.log('Seller footer loaded');
    
    // Simple toggle
    $('.btn-toggle').off('click').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('toggled');
        console.log('Toggle clicked, class toggled:', $('body').hasClass('toggled'));
    });
    
    // Sidebar close
    $('.sidebar-close').off('click').on('click', function() {
        $('body').removeClass('toggled');
        console.log('Sidebar closed');
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Inisialisasi MetisMenu
    if ($('#sidenav').length) {
        $('#sidenav').metisMenu({
            toggle: true, // Biarkan submenu lain tetap terbuka
            triggerElement: '.has-arrow' // Elemen yang memicu dropdown
        });
        console.log('MetisMenu initialized');
    }
    
    // Simple toggle
    $('.btn-toggle').off('click').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('toggled');
    });
});
</script>

</body>
</html>