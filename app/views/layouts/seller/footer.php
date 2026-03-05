<!--bootstrap js-->
<script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/jquery.min.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/metismenu/metisMenu.min.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/peity/jquery.peity.min.js"></script>
<script>
  $(".data-attributes span").peity("donut")
</script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
<script src="<?= BASE_URL ?>/assets/js/dashboard1.js"></script>
<script src="<?= BASE_URL ?>/assets/plugins/apexchart/apex-custom-chart.js"></script>
<script src="assets/js/data-widgets.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
  function updateChatBadge() {
    fetch('<?= BASE_URL ?>/?c=customerChat&m=getTotalUnread')
      .then(res => res.json())
      .then(data => {
        const badge = document.getElementById('chatBadge');

        if (data.total > 0) {
          badge.textContent = data.total;
          badge.style.display = 'inline-block';
        } else {
          badge.style.display = 'none';
        }
      });
  }

  updateChatBadge();
  setInterval(updateChatBadge, 5000);
</script>
</body>

</html>