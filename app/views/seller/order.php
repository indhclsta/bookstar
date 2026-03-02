<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<main class="main-wrapper">
  <div class="main-content">
    <!-- Breadcrumb -->
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
      <div>
        <h4 class="fw-semibold mb-1">Daftar Pesanan</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item active" aria-current="page">Orders</li>
          </ol>
        </nav>
      </div>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
      <script>
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: '<?= $_SESSION['success']; ?>',
          confirmButtonColor: '#3085d6'
        });
      </script>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
      <script>
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: '<?= $_SESSION['error']; ?>',
          confirmButtonColor: '#d33'
        });
      </script>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr class="text-secondary small">
                <th class="ps-4 py-3">Kode</th>
                <th class="py-3">Produk</th>
                <th class="py-3 text-center">Qty</th>
                <th class="py-3">Pembeli</th>
                <th class="py-3">Alamat</th>
                <th class="py-3 text-center">Metode</th>
                <th class="py-3 text-center">Bukti</th>
                <th class="py-3 text-center">Approval</th>
                <th class="py-3 text-center">Status</th>
                <th class="py-3 text-center">Resi</th>
                <th class="py-3 text-center">Lacak</th>
                <th class="pe-4 py-3 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $o): ?>
                <tr>
                  <td class="ps-4">
                    <span class="fw-medium"><?= $o['order_code'] ?></span>
                  </td>

                  <!-- Produk -->
                  <td>
                    <?php if (!empty($o['items'])): ?>
                      <?php foreach ($o['items'] as $item): ?>
                        <div class="mb-1"><?= htmlspecialchars($item['product_title']) ?></div>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <span class="text-secondary">-</span>
                    <?php endif; ?>
                  </td>

                  <!-- Qty -->
                  <td class="text-center">
                    <?php if (!empty($o['items'])): ?>
                      <?php foreach ($o['items'] as $item): ?>
                        <div class="mb-1"><?= $item['quantity'] ?>x</div>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <span class="text-secondary">-</span>
                    <?php endif; ?>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <i class="bi bi-person text-secondary"></i>
                      </div>
                      <span><?= htmlspecialchars($o['buyer_name'] ?? '-') ?></span>
                    </div>
                  </td>

                  <td>
                    <span class="text-truncate d-inline-block" style="max-width: 150px;" title="<?= htmlspecialchars($o['shipping_address'] ?? $o['buyer_address'] ?? '-') ?>">
                      <?= htmlspecialchars($o['shipping_address'] ?? $o['buyer_address'] ?? '-') ?>
                    </span>
                  </td>

                  <td class="text-center">
                    <span class="badge bg-light text-secondary px-3 py-2 rounded-pill">
                      <?= strtoupper($o['payment_method'] ?? '-') ?>
                    </span>
                  </td>

                  <td class="text-center">
                    <?php if (!empty($o['payment_proof'])): ?>
                      <a href="<?= BASE_URL ?>/uploads/payments/<?= $o['payment_proof'] ?>" target="_blank">
                        <img src="<?= BASE_URL ?>/uploads/payments/<?= $o['payment_proof'] ?>"
                          width="40"
                          height="40"
                          class="rounded-3 object-fit-cover border"
                          style="object-fit: cover;"
                          title="Klik untuk lihat bukti">
                      </a>
                    <?php else: ?>
                      <span class="text-secondary">-</span>
                    <?php endif; ?>
                  </td>

                  <td class="text-center">
                    <?php
                    $status = $o['approval_status'] ?? 'pending';
                    if ($status === 'approved'): ?>
                      <span class="badge bg-success px-3 py-2 rounded-pill">
                        <i class="bi bi-check-circle me-1 small"></i>Approved
                      </span>
                    <?php elseif ($status === 'rejected'): ?>
                      <span class="badge bg-danger px-3 py-2 rounded-pill">
                        <i class="bi bi-x-circle me-1 small"></i>Rejected
                      </span>
                    <?php else: ?>
                      <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                        <i class="bi bi-clock me-1 small"></i>Pending
                      </span>
                    <?php endif; ?>
                  </td>

                  <!-- Status -->
                  <td class="text-center">
                    <?php if ($o['order_status'] ?? '-'): ?>
                      <span class="badge bg-light text-secondary px-3 py-2 rounded-pill">
                        <?= ucfirst($o['order_status']) ?>
                      </span>
                    <?php else: ?>
                      <span class="text-secondary">-</span>
                    <?php endif; ?>
                  </td>

                  <!-- Resi -->
                  <td class="text-center">
                    <?php if (!empty($o['resi'])): ?>
                      <span class="fw-medium"><?= $o['resi'] ?></span>
                    <?php else: ?>
                      <span class="text-secondary">-</span>
                    <?php endif; ?>
                  </td>

                  <!-- Tracking URL -->
                  <td class="text-center">
                    <?php if (!empty($o['tracking_url'])): ?>
                      <a href="<?= $o['tracking_url'] ?>" target="_blank" class="text-decoration-none">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Lacak
                      </a>
                    <?php else: ?>
                      <span class="text-secondary">-</span>
                    <?php endif; ?>
                  </td>

                  <!-- Actions - Presisi -->
                  <td class="pe-4 text-center" style="min-width: 140px;">
                    <div class="d-flex gap-1 justify-content-center align-items-center">
                      <!-- Detail Button (only for pending) -->
                      <?php if (($o['approval_status'] ?? 'pending') === 'pending'): ?>
                        <button class="btn btn-light btn-sm rounded-circle btn-detail p-0 d-inline-flex align-items-center justify-content-center"
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
                          data-proof="<?= !empty($o['payment_proof']) ? BASE_URL . '/uploads/payments/' . $o['payment_proof'] : '' ?>"
                          title="Detail Pesanan"
                          data-bs-toggle="tooltip"
                          data-bs-placement="top"
                          style="width: 32px; height: 32px;">
                          <i class="bi bi-eye" style="font-size: 14px;"></i>
                        </button>
                      <?php endif; ?>

                      <!-- Input Resi Button (only for approved without resi) -->
                      <?php if (
                        ($o['approval_status'] ?? '') === 'approved'
                        && empty($o['resi'])
                        && empty($o['tracking_url'])
                      ): ?>
                        <button class="btn btn-light btn-sm rounded-circle btn-resi p-0 d-inline-flex align-items-center justify-content-center"
                          data-bs-toggle="modal"
                          data-bs-target="#resiModal"
                          data-id="<?= $o['id'] ?>"
                          title="Input Resi"
                          data-bs-toggle="tooltip"
                          data-bs-placement="top"
                          style="width: 32px; height: 32px;">
                          <i class="bi bi-truck" style="font-size: 14px;"></i>
                        </button>
                      <?php endif; ?>

                      <!-- Delete Button (only for rejected) -->
                      <?php
                      $canDelete = false;

                      if (
                        ($o['approval_status'] ?? '') === 'rejected'
                        && !empty($o['rejected_at'])
                      ) {
                        $selisih = time() - strtotime($o['rejected_at']);
                        if ($selisih >= 60) {
                          $canDelete = true;
                        }
                      }
                      ?>

                      <?php if ($canDelete): ?>
                        <a href="<?= BASE_URL ?>/?c=sellerOrder&m=delete&id=<?= $o['id'] ?>"
                          class="btn btn-light btn-sm rounded-circle text-danger p-0 d-inline-flex align-items-center justify-content-center btn-delete"
                          title="Hapus Pesanan"
                          data-bs-toggle="tooltip"
                          style="width: 32px; height: 32px;">
                          <i class="bi bi-trash" style="font-size: 14px;"></i>
                        </a>
                      <?php else: ?>
                        <?php if (($o['approval_status'] ?? '') === 'rejected' && !empty($o['rejected_at'])): ?>
                          <?php
                          $selisih = time() - strtotime($o['rejected_at']);
                          $sisa = max(0, 60 - $selisih);
                          ?>

                          <?php if ($sisa > 0): ?>
                            <span class="badge bg-secondary countdown-badge"
                              data-seconds="<?= $sisa ?>"
                              data-order="<?= $o['id'] ?>">
                              Tunggu <?= $sisa ?> detik
                            </span>
                          <?php endif; ?>
                        <?php endif; ?>
                      <?php endif; ?>

                      <!-- Chat Button (always visible) -->
                      <a href="<?= BASE_URL ?>/?c=sellerChat&m=index&userId=<?= $o['customer_id'] ?>"
                        class="btn btn-light btn-sm rounded-circle p-0 d-inline-flex align-items-center justify-content-center"
                        title="Chat Pembeli"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        style="width: 32px; height: 32px;">
                        <i class="bi bi-chat" style="font-size: 14px;"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>

              <?php if (empty($orders)): ?>
                <tr>
                  <td colspan="12" class="text-center py-5">
                    <div class="text-secondary">
                      <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                      <p class="mt-3 mb-0">Belum ada pesanan</p>
                      <small>Pesanan akan muncul ketika ada pembeli yang memesan produk Anda</small>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- MODAL DETAIL PESANAN -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-semibold">Detail Pesanan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body pt-3">
        <!-- Order Info -->
        <div class="bg-light rounded-3 p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-secondary">Kode Pesanan</small>
            <span class="fw-semibold" id="d_code"></span>
          </div>
        </div>

        <!-- Product Info -->
        <div class="mb-3">
          <small class="text-secondary d-block mb-2">Produk</small>
          <div class="bg-light rounded-3 p-3">
            <span id="d_title"></span>
          </div>
        </div>

        <!-- Buyer Info -->
        <div class="row g-3 mb-3">
          <div class="col-12">
            <small class="text-secondary d-block mb-2">Informasi Pembeli</small>
            <div class="bg-light rounded-3 p-3">
              <div class="d-flex align-items-center mb-2">
                <i class="bi bi-person me-2 text-secondary"></i>
                <span id="d_name"></span>
              </div>
              <div class="d-flex">
                <i class="bi bi-geo-alt me-2 text-secondary"></i>
                <span id="d_address"></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Proof -->
        <div class="mb-3" id="proofContainer">
          <small class="text-secondary d-block mb-2">Bukti Pembayaran</small>
          <div class="text-center bg-light rounded-3 p-3">
            <img id="d_proof" class="img-fluid rounded-3" style="max-height: 200px; object-fit: contain;">
          </div>
        </div>

        <!-- Reject Reason (hidden by default) -->
        <div id="rejectReasonWrapper" class="mb-3 d-none">
          <small class="text-secondary d-block mb-2">Alasan Penolakan</small>
          <textarea id="rejectReasonInput" class="form-control" placeholder="Masukkan alasan penolakan..." rows="3"></textarea>
        </div>
      </div>

      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>

        <!-- Approval Buttons -->
        <div id="approvalButtons" class="d-inline">
          <a id="btnApprove" class="btn btn-success">Setujui</a>
          <button id="btnReject" class="btn btn-danger">Tolak</button>
          <button id="btnConfirmReject" class="btn btn-danger d-none">Konfirmasi Penolakan</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL INPUT RESI -->
<div class="modal fade" id="resiModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="post" action="<?= BASE_URL ?>/?c=sellerOrder&m=inputResi" class="modal-content border-0 shadow">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-semibold">Input Nomor Resi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body pt-3">
        <input type="hidden" name="order_id" id="resi_order_id">

        <div class="mb-3">
          <label class="form-label small fw-semibold text-secondary">Nomor Resi</label>
          <input type="text" name="resi" class="form-control" required placeholder="Masukkan nomor resi">
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold text-secondary">Tracking URL (Opsional)</label>
          <input type="text" name="tracking_url" class="form-control" placeholder="https://...">
          <small class="text-secondary mt-1 d-block">Link untuk melacak pengiriman</small>
        </div>
      </div>

      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary px-4">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Detail modal handler
    document.querySelectorAll('.btn-detail').forEach(btn => {
      btn.addEventListener('click', function() {
        const modal = document.getElementById('detailModal');

        modal.querySelector('#d_code').innerText = this.dataset.code;
        modal.querySelector('#d_title').innerText = this.dataset.title;
        modal.querySelector('#d_name').innerText = this.dataset.name;
        modal.querySelector('#d_address').innerText = this.dataset.address;

        const proofImg = modal.querySelector('#d_proof');
        const proofContainer = modal.querySelector('#proofContainer');

        if (this.dataset.proof) {
          proofImg.src = this.dataset.proof;
          proofContainer.classList.remove('d-none');
        } else {
          proofContainer.classList.add('d-none');
        }

        const approveBtn = modal.querySelector('#btnApprove');
        approveBtn.href = "<?= BASE_URL ?>/?c=sellerOrder&m=approve&id=" + this.dataset.id;

        const rejectBtn = modal.querySelector('#btnReject');
        rejectBtn.dataset.id = this.dataset.id;

        // Reset reject section
        modal.querySelector('#rejectReasonWrapper').classList.add('d-none');
        modal.querySelector('#rejectReasonInput').value = '';
        modal.querySelector('#btnConfirmReject').classList.add('d-none');
        rejectBtn.classList.remove('d-none');
        approveBtn.classList.remove('d-none');
      });
    });

    // Reject button handler
    const rejectBtn = document.getElementById('btnReject');
    const confirmRejectBtn = document.getElementById('btnConfirmReject');
    const approveBtn = document.getElementById('btnApprove');
    const rejectWrapper = document.getElementById('rejectReasonWrapper');
    const rejectInput = document.getElementById('rejectReasonInput');

    if (rejectBtn) {
      rejectBtn.addEventListener('click', function() {
        rejectWrapper.classList.remove('d-none');
        confirmRejectBtn.classList.remove('d-none');
        this.classList.add('d-none');
        approveBtn.classList.add('d-none');
      });
    }

    // Confirm reject handler
    if (confirmRejectBtn) {
      confirmRejectBtn.addEventListener('click', function() {
        const reason = rejectInput.value;
        const orderId = rejectBtn.dataset.id;

        if (!reason.trim()) {
          Swal.fire({
            icon: 'warning',
            title: 'Alasan diperlukan',
            text: 'Harap masukkan alasan penolakan',
            confirmButtonColor: '#3085d6'
          });
          return;
        }

        window.location.href = "<?= BASE_URL ?>/?c=sellerOrder&m=reject&id=" + orderId + "&reason=" + encodeURIComponent(reason);
      });
    }

    // Resi modal handler
    document.querySelectorAll('.btn-resi').forEach(btn => {
      btn.addEventListener('click', function() {
        document.getElementById('resi_order_id').value = this.dataset.id;
      });
    });

    // Delete confirmation
    document.querySelectorAll('.btn-delete').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const href = this.href;

        Swal.fire({
          title: 'Hapus Pesanan?',
          text: 'Pesanan yang sudah ditolak akan dihapus',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, hapus',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = href;
          }
        });
      });
    });
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.countdown-badge').forEach(function(badge) {
      let seconds = parseInt(badge.dataset.seconds);
      const orderId = badge.dataset.order;

      const interval = setInterval(function() {
        seconds--;

        if (seconds > 0) {
          badge.innerText = "Tunggu " + seconds + " detik";
        } else {
          clearInterval(interval);

          // ganti badge jadi tombol delete
          badge.outerHTML = `
          <a href="<?= BASE_URL ?>/?c=sellerOrder&m=delete&id=${orderId}"
             class="btn btn-light btn-sm rounded-circle text-danger p-0 d-inline-flex align-items-center justify-content-center btn-delete"
             style="width:32px;height:32px;">
             <i class="bi bi-trash" style="font-size:14px;"></i>
          </a>
        `;
        }
      }, 1000);
    });

  });
</script>

<style>
  /* Custom styles for clean minimal look */
  .table thead th {
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    border-bottom-width: 1px;
    white-space: nowrap;
  }

  .table tbody td {
    padding: 1rem 0.5rem;
    vertical-align: middle;
    font-size: 0.875rem;
  }

  /* Action buttons - precise and consistent */
  .btn-light.rounded-circle {
    background-color: #f8f9fa;
    border-color: #f8f9fa;
    transition: all 0.2s;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin: 0 2px;
    flex: 0 0 auto;
  }

  .btn-light.rounded-circle i {
    font-size: 14px;
    line-height: 1;
    display: inline-block;
  }

  .btn-light.rounded-circle:hover {
    background-color: #e9ecef;
    border-color: #e9ecef;
  }

  .btn-light.rounded-circle.text-danger:hover {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    color: white !important;
  }

  /* Container for action buttons */
  td .d-flex {
    gap: 4px !important;
    flex-wrap: nowrap;
    min-height: 32px;
  }

  /* Ensure all buttons are perfectly aligned */
  .d-flex.justify-content-center.align-items-center {
    min-height: 32px;
  }

  /* Badge styles */
  .badge {
    font-weight: 500;
    font-size: 0.75rem;
  }

  .badge.bg-success {
    background-color: #28a745 !important;
  }

  .badge.bg-danger {
    background-color: #dc3545 !important;
  }

  .badge.bg-warning {
    background-color: #ffc107 !important;
  }

  /* Modal styles */
  .modal-content {
    border-radius: 1rem;
  }

  .modal-header {
    padding: 1.5rem 1.5rem 0.5rem;
  }

  .modal-body {
    padding: 1.5rem;
  }

  .modal-footer {
    padding: 0 1.5rem 1.5rem;
  }

  .bg-light {
    background-color: #f8f9fa !important;
  }

  .rounded-3 {
    border-radius: 0.5rem !important;
  }

  .object-fit-cover {
    object-fit: cover;
  }

  .text-truncate {
    max-width: 150px;
  }

  /* SweetAlert2 customization */
  .swal2-popup {
    border-radius: 1rem;
  }

  .swal2-title {
    font-size: 1.25rem;
    font-weight: 600;
  }

  /* Hover effects */
  .table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, .02);
  }

  /* Link styles */
  a.text-decoration-none {
    color: #6c757d;
  }

  a.text-decoration-none:hover {
    color: #0d6efd;
  }

  /* Tooltip customization */
  .tooltip {
    font-size: 0.75rem;
  }

  .tooltip .tooltip-inner {
    background-color: #212529;
    padding: 4px 8px;
    border-radius: 4px;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .table tbody td {
      white-space: nowrap;
    }

    td .d-flex {
      min-width: 130px;
    }
  }
</style>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>