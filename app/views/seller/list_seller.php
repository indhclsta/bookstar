<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<main class="main-wrapper">
  <div class="main-content">

    <div class="page-breadcrumb mb-3">
      <h5>Seller List</h5>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table align-middle table-striped">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Seller</th>
                <th>Email</th>
                <th>NIK</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>

              <?php if (!empty($sellers)): ?>
                <?php foreach ($sellers as $i => $s): ?>
                  <tr>
                    <td><?= $i + 1 ?></td>

                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <img src="<?= !empty($s['photo'])
                          ? BASE_URL . '/uploads/profile/' . $s['photo']
                          : 'https://placehold.co/60x60/png' ?>"
                          class="rounded-circle"
                          width="45" height="45">

                        <div>
                          <div class="fw-semibold"><?= htmlspecialchars($s['name']) ?></div>
                          <small class="text"><?= htmlspecialchars($s['address']) ?></small>
                        </div>
                      </div>
                    </td>

                    <td><?= htmlspecialchars($s['email']) ?></td>
                    <td><?= htmlspecialchars($s['nik']) ?></td>

                    <td>
                      <?php if ($s['is_online']): ?>
                        <span class="badge bg-success">Online</span>
                      <?php else: ?>
                        <span class="badge bg-secondary">Offline</span>
                      <?php endif; ?>
                    </td>

                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center text-muted">
                    Belum ada seller
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

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>
