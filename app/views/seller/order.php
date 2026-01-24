<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<main class="main-wrapper">
  <div class="main-content">

    <h4 class="mb-4">Daftar Pesanan</h4>

    <?php if (!empty($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="card">
      <div class="card-body table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Kode</th>
              <th>Judul Buku</th>
              <th>Qty</th>
              <th>Nama Pembeli</th>
              <th>Alamat</th>
              <th>Metode</th>
              <th>Bukti</th>
              <th>Approve</th>
              <th>Status</th>
              <th>Resi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $o): ?>
            <tr>
              <td><?= $o['order_code'] ?></td>
              <td><?= htmlspecialchars($o['product_title']) ?></td>
              <td><?= $o['qty'] ?></td>
              <td><?= htmlspecialchars($o['buyer_name']) ?></td>
              <td><?= htmlspecialchars($o['shipping_address'] ?? $o['buyer_address']) ?></td>
              <td><?= strtoupper($o['payment_method']) ?></td>
              <td>
                <?php if (!empty($o['payment_proof'])): ?>
                  <img src="<?= BASE_URL ?>/uploads/payments/<?= $o['payment_proof'] ?>" width="60" class="rounded">
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td>
                <span class="badge bg-<?= 
                  $o['approval_status'] === 'approved' ? 'success' :
                  ($o['approval_status'] === 'rejected' ? 'danger' : 'warning')
                ?>">
                  <?= ucfirst($o['approval_status']) ?>
                </span>
              </td>
              <td><?= ucfirst($o['order_status']) ?></td>
              <td><?= $o['tracking_number'] ?? '-' ?></td>
              <td class="d-flex gap-1">
                <!-- DETAIL -->
                <button class="btn btn-sm btn-info btn-detail"
                        data-bs-toggle="modal"
                        data-bs-target="#detailModal"
                        data-code="<?= $o['order_code'] ?>"
                        data-title="<?= htmlspecialchars($o['product_title']) ?>"
                        data-name="<?= htmlspecialchars($o['buyer_name']) ?>"
                        data-address="<?= htmlspecialchars($o['shipping_address'] ?? $o['buyer_address']) ?>"
                        data-proof="<?= BASE_URL ?>/uploads/payments/<?= $o['payment_proof'] ?? '' ?>">
                  Detail
                </button>

                <!-- INPUT RESI -->
                <?php if ($o['approval_status'] === 'approved'): ?>
                <button class="btn btn-sm btn-primary btn-resi"
                        data-bs-toggle="modal"
                        data-bs-target="#resiModal"
                        data-id="<?= $o['id'] ?>">
                  Input Resi
                </button>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</main>

<!-- MODAL DETAIL -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Pesanan</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><b>Kode:</b> <span id="d_code"></span></p>
        <p><b>Judul Buku:</b> <span id="d_title"></span></p>
        <p><b>Nama Pembeli:</b> <span id="d_name"></span></p>
        <p><b>Alamat:</b> <span id="d_address"></span></p>
        <img id="d_proof" class="img-fluid rounded">
      </div>
      <div class="modal-footer">
        <a id="btnApprove" class="btn btn-success">Setujui</a>
        <a id="btnReject" class="btn btn-danger">Tolak</a>
      </div>
    </div>
  </div>
</div>

<!-- MODAL RESI -->
<div class="modal fade" id="resiModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" action="<?= BASE_URL ?>/?c=sellerOrder&m=inputResi" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Input Nomor Resi</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="order_id" id="resi_order_id">
        <div class="mb-3">
          <label>No Resi</label>
          <input type="text" name="resi" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Lacak Paket</label>
          <input type="text" name="tracking_url" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.btn-detail').forEach(btn => {
    btn.onclick = () => {
      d_code.innerText    = btn.dataset.code;
      d_title.innerText   = btn.dataset.title;
      d_name.innerText    = btn.dataset.name;
      d_address.innerText = btn.dataset.address;
      d_proof.src         = btn.dataset.proof;
      btnApprove.href = "<?= BASE_URL ?>/?c=sellerOrder&m=approve&code=" + btn.dataset.code;
      btnReject.href  = "<?= BASE_URL ?>/?c=sellerOrder&m=reject&code=" + btn.dataset.code;
    };
  });

  document.querySelectorAll('.btn-resi').forEach(btn => {
    btn.onclick = () => {
      document.getElementById('resi_order_id').value = btn.dataset.id;
    };
  });
});
</script>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>
