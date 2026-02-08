<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<main class="main-wrapper py-4">
  <div class="container">
    <h4 class="mb-4">Daftar Pesanan</h4>

    <?php if (!empty($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= $_SESSION['success'];
                                        unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error'];
                                      unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm rounded-4 border-0">
      <div class="card-body table-responsive p-3">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr class="text-center text-muted small">
              <th>Kode</th>
              <th>Produk</th>
              <th>Qty</th>
              <th>Pembeli</th>
              <th>Alamat</th>
              <th>Metode</th>
              <th>Bukti</th>
              <th>Approval</th>
              <th>Status</th>
              <th>Resi</th>
              <th>Lacak</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $o): ?>
              <tr class="text-center">
                <td><?= $o['order_code'] ?></td>

                <!-- Produk -->
                <td>
                  <?php if (!empty($o['items'])): ?>
                    <?php foreach ($o['items'] as $item): ?>
                      <?= htmlspecialchars($item['product_title']) ?><br>
                    <?php endforeach; ?>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>

                <!-- Qty -->
                <td>
                  <?php if (!empty($o['items'])): ?>
                    <?php foreach ($o['items'] as $item): ?>
                      <?= $item['quantity'] ?><br>
                    <?php endforeach; ?>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>

                <td><?= htmlspecialchars($o['buyer_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($o['shipping_address'] ?? $o['buyer_address'] ?? '-') ?></td>
                <td><?= strtoupper($o['payment_method'] ?? '-') ?></td>

                <td>
                  <?php if (!empty($o['payment_proof'])): ?>
                    <img src="<?= BASE_URL ?>/uploads/payments/<?= $o['payment_proof'] ?>" width="50" class="rounded">
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>

                <td>
                  <?php
                  $status = $o['approval_status'] ?? 'pending';
                  if ($status === 'approved') {
                    echo '<span class="badge bg-success">Approved <i class="bi bi-check2 ms-2"></i></span>';
                  } elseif ($status === 'rejected') {
                    echo '<span class="badge bg-danger">Rejected <i class="bi bi-x-lg ms-2"></i></span>';
                  } else {
                    echo '<span class="badge bg-warning text-dark">Pending <i class="bi bi-info-circle ms-2"></i></span>';
                  }
                  ?>
                </td>


                <!-- Status -->
                <td><?= ucfirst($o['order_status'] ?? '-') ?></td>

                <!-- Resi -->
                <td><?= $o['resi'] ?? '-' ?></td>

                <!-- Tracking URL -->
                <td>
                  <?php if (!empty($o['tracking_url'])): ?>
                    <a href="<?= $o['tracking_url'] ?>" target="_blank">Lacak</a>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>

                <td class="d-flex justify-content-center gap-1">

                  <!-- DETAIL hanya muncul jika masih pending -->
                  <?php if (($o['approval_status'] ?? 'pending') === 'pending'): ?>
                    <button class="btn btn-sm btn-info btn-detail"
                      data-bs-toggle="modal"
                      data-bs-target="#detailModal"
                      data-id="<?= $o['id'] ?>"
                      data-code="<?= $o['order_code'] ?>"
                      data-title="<?php
                                  if (!empty($o['items'])) {
                                    $titles = array_map(fn($i) => $i['product_title'], $o['items']);
                                    echo htmlspecialchars(implode(', ', $titles));
                                  } else {
                                    echo '-';
                                  }
                                  ?>"
                      data-name="<?= htmlspecialchars($o['buyer_name'] ?? '-') ?>"
                      data-address="<?= htmlspecialchars($o['shipping_address'] ?? $o['buyer_address'] ?? '-') ?>"
                      data-proof="<?= BASE_URL ?>/uploads/payments/<?= $o['payment_proof'] ?? '' ?>">
                      Detail
                    </button>
                  <?php endif; ?>

                  <?php if (
                    ($o['approval_status'] ?? '') === 'approved'
                    && empty($o['resi'])
                    && empty($o['tracking_url'])
                  ): ?>
                    <button class="btn btn-sm btn-primary btn-resi"
                      data-bs-toggle="modal"
                      data-bs-target="#resiModal"
                      data-id="<?= $o['id'] ?>">
                      Input Resi
                    </button>
                  <?php endif; ?>


                  <!-- DELETE hanya jika REJECTED -->
                  <?php if (($o['approval_status'] ?? '') === 'rejected'): ?>
                    <a href="<?= BASE_URL ?>/?c=sellerOrder&m=delete&id=<?= $o['id'] ?>"
                      class="btn btn-sm btn-danger"
                      onclick="return confirm('Yakin ingin menghapus pesanan ini?')">
                      Delete
                    </a>
                  <?php endif; ?>

                  <!-- CHAT selalu ada -->
                  <a href="<?= BASE_URL ?>/?c=sellerChat&m=index&order_id=<?= $o['id'] ?>"
                    class="btn btn-sm btn-secondary">
                    Chat
                  </a>

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
        <p><b>Produk:</b> <span id="d_title"></span></p>
        <p><b>Pembeli:</b> <span id="d_name"></span></p>
        <p><b>Alamat:</b> <span id="d_address"></span></p>
        <img id="d_proof" class="img-fluid rounded mt-2">

        <!-- Alasan Tolak (hanya muncul saat klik Tolak) -->
        <div id="rejectReasonWrapper" class="mt-3 d-none">
          <label>Alasan Penolakan</label>
          <textarea id="rejectReasonInput" class="form-control" placeholder="Masukkan alasan penolakan..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <a id="btnApprove" class="btn btn-success">Setujui</a>
        <button id="btnReject" class="btn btn-danger">Tolak</button>
        <button id="btnConfirmReject" class="btn btn-danger d-none">Kirim Tolak</button>
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
          <label>Tracking URL</label>
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
        d_code.innerText = btn.dataset.code;
        d_title.innerText = btn.dataset.title;
        d_name.innerText = btn.dataset.name;
        d_address.innerText = btn.dataset.address;
        d_proof.src = btn.dataset.proof;
        btnApprove.href = "<?= BASE_URL ?>/?c=sellerOrder&m=approve&id=" + btn.dataset.id;
        btnReject.dataset.id = btn.dataset.id;

        // reset reason
        document.getElementById('rejectReasonWrapper').classList.add('d-none');
        document.getElementById('rejectReasonInput').value = '';
        document.getElementById('btnConfirmReject').classList.add('d-none');
      };
    });

    // Klik Tolak → tampilkan textarea dan tombol konfirmasi
    btnReject.onclick = () => {
      document.getElementById('rejectReasonWrapper').classList.remove('d-none');
      document.getElementById('btnConfirmReject').classList.remove('d-none');
      btnReject.classList.add('d-none');
    };

    // Klik Konfirmasi Tolak → kirim ke backend
    btnConfirmReject.onclick = () => {
      const reason = document.getElementById('rejectReasonInput').value;
      const orderId = btnReject.dataset.id;
      window.location.href = "<?= BASE_URL ?>/?c=sellerOrder&m=reject&id=" + orderId + "&reason=" + encodeURIComponent(reason);
    };
  });


  // Isi modal resi
  document.querySelectorAll('.btn-resi').forEach(btn => {
    btn.onclick = () => {
      document.getElementById('resi_order_id').value = btn.dataset.id;
    };
  });
</script>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>