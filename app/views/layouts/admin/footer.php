<!--bootstrap js-->
<script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/jquery.min.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/metismenu/metisMenu.min.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/peity/jquery.peity.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
<script src="<?= BASE_URL ?>/assets/js/dashboard1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Simple toggle
    $('.btn-toggle').off('click').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('toggled');
        console.log('Toggle clicked');
    });
});
</script>
</body>
</html>
