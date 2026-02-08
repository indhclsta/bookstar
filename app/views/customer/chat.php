<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>

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
            <?php foreach ($sellers as $s): ?>
              <a href="<?= BASE_URL ?>/?c=customerChat&m=index&userId=<?= $s['id'] ?>"
                class="list-group-item list-group-item-action <?= ($chatWith['id'] == $s['id']) ? 'active' : '' ?>">
                <div class="d-flex align-items-center">
                  <!-- <img src="<?= $s['photo'] ?? 'https://placehold.co/110x110/png' ?>" width="42" height="42" class="rounded-circle me-2" alt=""> -->
                  <div class="flex-grow-1">
                    <h6 class="mb-0"><?= htmlspecialchars($s['name']) ?></h6>
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
            <h6 class="mb-0"><?= $chatWith['name'] ?></h6>
            <small class="text-success"><?= $chatWith['status'] ?? 'Offline' ?></small>
          </div>
        </div>

        <div class="chat-content flex-grow-1 overflow-auto" id="chatBox">
          <!-- Tambahkan spacer di atas -->
          <div style="height: 20px;"></div>
          
          <div class="px-3">
            <?php if (!empty($messages)): ?>
              <?php foreach ($messages as $msg): ?>
                <?php if ($msg['sender_id'] == $_SESSION['user']['id']): ?>
                  <!-- Pesan dari customer sendiri (kanan) -->
                  <div class="chat-content-rightside d-flex justify-content-end mb-4">
                    <div class="d-flex flex-column align-items-end" style="max-width: 70%;">
                      <div class="d-flex align-items-center w-100 mb-1">
                        <h6 class="mb-0 small fw-bold me-1">You</h6>
                        <span class="small text">, <?= date('g:i A', strtotime($msg['created_at'])) ?></span>
                      </div>
                      <div class="d-flex align-items-end">
                        <div class="flex-grow-1 me-2">
                          <p class="chat-right-msg bg-primary text-white p-3 rounded mb-0">
                            <?= nl2br(htmlspecialchars($msg['message'])) ?>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php else: ?>
                  <!-- Pesan dari seller (kiri) -->
                  <div class="chat-content-leftside d-flex mb-4">
                    <div class="d-flex flex-column" style="max-width: 70%;">
                      <div class="d-flex align-items-center mb-1">
                        <h6 class="mb-0 small fw-bold me-1"><?= htmlspecialchars($chatWith['name']) ?></h6>
                        <span class="small text">, <?= date('g:i A', strtotime($msg['created_at'])) ?></span>
                      </div>
                      <div class="d-flex align-items-start">
                        <!-- Foto seller -->
                        <!-- <img src="<?= !empty($chatWith['photo']) ? $chatWith['photo'] : 'https://placehold.co/110x110/png' ?>" 
                             width="40" height="40" 
                             class="rounded-circle me-2 mt-1" 
                             alt="<?= htmlspecialchars($chatWith['name']) ?>"> -->
                        <div class="flex-grow-1">
                          <p class="chat-left-msg bg-light p-3 rounded mb-0">
                            <?= nl2br(htmlspecialchars($msg['message'])) ?>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 200px);">
                <p class="text-center text">No messages yet. Start the conversation!</p>
              </div>
            <?php endif; ?>
          </div>
          
          <!-- Tambahkan spacer di bawah -->
          <div style="height: 20px;"></div>
        </div>

        <?php if ($chatWith['id']): ?>
          <div class="chat-footer d-flex align-items-center p-3 border-top">
            <form action="<?= BASE_URL ?>/?c=customerChat&m=send" method="POST" class="d-flex flex-grow-1 gap-2">
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

<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>