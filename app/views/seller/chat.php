<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<main class="main-wrapper">
  <div class="main-content">
    <div class="chat-wrapper d-flex">
      <!-- Sidebar -->
      <div class="chat-sidebar border-end">
        <div class="chat-sidebar-header p-3">
          <h5>Chats</h5>
          <input type="text" class="form-control form-control-sm mt-2" placeholder="Search chats...">
        </div>
        <div class="chat-sidebar-content">
          <div class="list-group list-group-flush">
            <?php foreach ($chatUsers as $user): ?>
              <a href="<?= BASE_URL ?>/?c=sellerChat&m=index&userId=<?= $user['id'] ?>" class="list-group-item list-group-item-action <?= ($chatWith['id'] == $user['id']) ? 'active' : '' ?>">
                <div class="d-flex align-items-center">
                  <!-- <img src="<?= !empty($user['photo']) ? $user['photo'] : 'https://placehold.co/110x110/png' ?>" width="42" height="42" class="rounded-circle me-2" alt=""> -->
                  <div class="flex-grow-1">
                    <h6 class="mb-0"><?= htmlspecialchars($user['name']) ?></h6>
                    <p class="mb-0 text-truncate">
                      <?php
                      $lastMsg = '';
                      foreach ($messages as $msg) {
                        if ($msg['sender_id'] == $user['id'] || $msg['receiver_id'] == $user['id']) {
                          $lastMsg = $msg['message'];
                        }
                      }
                      echo htmlspecialchars($lastMsg);
                      ?>
                    </p>
                  </div>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Chat Window -->
      <div class="chat-window flex-grow-1 d-flex flex-column">
        <div class="chat-header p-3 border-bottom d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-0"><?= htmlspecialchars($chatWith['name'] ?? 'Select a chat') ?></h6>
            <small class="text-success"><?= $chatWith['status'] ?? 'Offline' ?></small>
          </div>
        </div>

        <div class="chat-content flex-grow-1 overflow-auto" id="chatBox">
          <!-- Tambahkan div kosong sebagai spacer di atas -->
          <div style="height: 20px;"></div>

          <div class="px-3">
            <?php if (!empty($messages)): ?>
              <?php foreach ($messages as $msg): ?>
                <?php if ($msg['sender_id'] == $_SESSION['user']['id']): ?>
                  <!-- Pesan dari user sendiri (kanan) -->
                  <div class="chat-content-rightside d-flex justify-content-end mb-3">
                    <div class="d-flex align-items-end">
                      <div class="flex-grow-1 me-2 text-end">
                        <p class="mb-0 chat-time text small"><?= date('H:i, d M', strtotime($msg['created_at'])) ?></p>
                        <p class="chat-right-msg bg-primary text-white p-3 rounded d-inline-block mb-0">
                          <?= nl2br(htmlspecialchars($msg['message'])) ?>
                        </p>
                      </div>
                    </div>
                  </div>
                <?php else: ?>
                  <!-- Pesan dari lawan bicara (kiri) -->
                  <div class="chat-content-leftside d-flex mb-3">
                    <!-- <img src="<?= !empty($chatWith['photo']) ? $chatWith['photo'] : 'https://placehold.co/110x110/png' ?>"
                      width="48" height="48"
                      class="rounded-circle me-2"
                      alt=""> -->
                    <div class="flex-grow-1">
                      <p class="mb-0 chat-time text small"><?= date('H:i, d M', strtotime($msg['created_at'])) ?></p>
                      <p class="chat-left-msg bg-light p-3 rounded mb-0">
                        <?= nl2br(htmlspecialchars($msg['message'])) ?>
                      </p>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 200px);">
                <p class="text-center text-muted">No messages yet. Start the conversation!</p>
              </div>
            <?php endif; ?>
          </div>

          <!-- Tambahkan div kosong sebagai spacer di bawah -->
          <div style="height: 20px;"></div>
        </div>

        <!-- Chat Input -->
        <?php if (!empty($chatWith['id'])): ?>
          <div class="chat-footer d-flex align-items-center p-3 border-top">
            <form action="<?= BASE_URL ?>/?c=sellerChat&m=send" method="POST" class="d-flex flex-grow-1 gap-2">
              <input type="hidden" name="receiver_id" value="<?= $chatWith['id'] ?>">
              <div class="input-group flex-grow-1">
                <span class="input-group-text"><i class='bx bx-smile'></i></span>
                <input type="text" name="message" class="form-control" placeholder="Type a message" required>
              </div>
              <button class="btn btn-primary">Send</button>
            </form>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<script>
  // Tunggu sampai halaman selesai dimuat
  document.addEventListener('DOMContentLoaded', function() {
    const chatBox = document.getElementById('chatBox');
    // Berikan sedikit delay untuk memastikan semua konten sudah dirender
    setTimeout(() => {
      // Scroll langsung ke bawah
      chatBox.scrollTop = chatBox.scrollHeight;
    }, 200);
  });
</script>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>