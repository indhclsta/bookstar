<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>

<main class="main-wrapper">
  <div class="container-fluid py-3 px-4">
    <!-- Card Container untuk Chat -->
    <div class="card border-0 rounded-3 overflow-hidden" style="height: calc(100vh - 100px); background-color: #0A0F1C;">
      <div class="row g-0 h-100">
        <!-- Sidebar Chats -->
        <div class="col-12 col-md-4 col-lg-3" style="background-color: #141B2B; border-right: 1px solid #1E2A3A;">
          <div class="d-flex flex-column h-100">
            <!-- Header -->
            <div class="p-3" style="background-color: #1E2A3A; border-bottom: 1px solid #2A3A4F;">
              <h5 class="mb-3" style="color: #E5E9F0; font-weight: 500;">Chats</h5>
              <input type="text" class="form-control form-control-sm" placeholder="Search chats..."
                style="background-color: #0F1625; border: 1px solid #2A3A4F; border-radius: 8px; padding: 0.5rem; color: #E5E9F0;">
            </div>

            <!-- Chat List -->
            <div class="flex-grow-1 overflow-auto" style="background-color: #141B2B;">
              <?php if (!empty($sellers)): ?>
                <?php foreach ($sellers as $s): ?>
                  <a href="<?= BASE_URL ?>/?c=customerChat&m=index&userId=<?= $s['id'] ?>" class="text-decoration-none chat-link">
                    <div class="p-3"
                      style="border-bottom: 1px solid #1E2A3A;
      <?= ($chatWith['id'] == $s['id']) ? 'background-color: #1E3A5F;' : 'background-color: #141B2B;' ?>">

                      <div class="d-flex align-items-center">

                        <!-- Avatar -->
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                          style="width: 42px; height: 42px; min-width: 42px; background-color: #2A6DF4; color: #FFFFFF;">
                          <span class="fw-semibold">
                            <?= strtoupper(substr($s['name'], 0, 1)) ?>
                          </span>
                        </div>

                        <!-- Info -->
                        <div class="ms-3 flex-grow-1 min-width-0">

                          <!-- Nama + Badge -->
                          <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"
                              style="color: #E5E9F0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                              <?= htmlspecialchars($s['name']) ?>
                            </h6>

                            <span
                              class="badge bg-danger rounded-pill unread-badge"
                              data-user-id="<?= $s['id'] ?>"
                              style="display:none; min-width: 20px;">
                            </span>
                          </div>

                          <!-- Preview Message -->
                          <p class="mb-0 small"
                            style="color: #8A9BB5; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?php
                            if (!empty($s['last_message'])) {

                              if ($s['last_sender_id'] == $_SESSION['user']['id']) {
                                echo "You: ";
                              }

                              echo htmlspecialchars(strlen($s['last_message']) > 25
                                ? substr($s['last_message'], 0, 25) . '...'
                                : $s['last_message']);
                            } else {
                              echo 'No messages yet';
                            }
                            ?>
                          </p>

                        </div>
                      </div>

                    </div>
                  </a>
                <?php endforeach; ?>

              <?php else: ?>

                <div class="text-center py-5 px-3">
                  <i class="bi bi-chat-dots" style="font-size: 2.5rem; color: #2A3A4F;"></i>
                  <p class="mt-3 mb-0" style="color: #8A9BB5;">No chats yet</p>
                  <small style="color: #546E8A;">Chats will appear when you contact a seller</small>
                </div>

              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Chat Window -->
        <div class="col-12 col-md-8 col-lg-9" style="background-color: #0A0F1C;">
          <?php if (!empty($chatWith['id'])): ?>
            <div class="d-flex flex-column h-100">
              <!-- Chat Header -->
              <div class="p-3" style="background-color: #141B2B; border-bottom: 1px solid #1E2A3A;">
                <div class="d-flex align-items-center">
                  <button class="btn btn-link d-md-none p-0 me-3" onclick="toggleSidebar()" style="color: #8A9BB5;">
                    <i class="bi bi-arrow-left"></i>
                  </button>
                  <div class="position-relative">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                      style="width: 42px; height: 42px; min-width: 42px; background-color: #2A6DF4; color: #FFFFFF;">
                      <span class="fw-semibold"><?= strtoupper(substr($chatWith['name'] ?? 'U', 0, 1)) ?></span>
                    </div>
                  </div>
                  <div>
                    <h6 class="mb-0" style="color: #E5E9F0;"><?= htmlspecialchars($chatWith['name'] ?? '') ?></h6>
                  </div>
                </div>
              </div>

              <!-- Messages -->
              <div class="flex-grow-1 overflow-auto p-4" id="chatBox" style="background-color: #0A0F1C; min-height: 0;">
                <div class="d-flex flex-column" style="min-height: 100%;">
                  <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $msg): ?>
                      <?php if ($msg['sender_id'] == $_SESSION['user']['id']): ?>
                        <!-- My Message (Customer) - Kanan -->
                        <div class="d-flex justify-content-end mb-3">
                          <div style="max-width: 70%;">
                            <div class="d-flex justify-content-end align-items-center mb-1">
                              <span class="small" style="color: #546E8A;"><?= date('g:i A', strtotime($msg['created_at'])) ?></span>
                              <h6 class="mb-0 small fw-bold ms-1" style="color: #8A9BB5;">You</h6>
                            </div>
                            <div class="p-3 rounded-3" style="background-color: #2A6DF4; color: #FFFFFF;">
                              <p class="mb-0"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                            </div>
                          </div>
                        </div>
                      <?php else: ?>
                        <!-- Their Message (Seller) - Kiri -->
                        <div class="d-flex mb-3">
                          <div style="max-width: 70%;">
                            <div class="d-flex align-items-center mb-1">
                              <h6 class="mb-0 small fw-bold me-1" style="color: #8A9BB5;"><?= htmlspecialchars($chatWith['name'] ?? 'Seller') ?></h6>
                              <span class="small" style="color: #546E8A;"><?= date('g:i A', strtotime($msg['created_at'])) ?></span>
                            </div>
                            <div class="p-3 rounded-3" style="background-color: #141B2B; color: #E5E9F0;">
                              <p class="mb-0"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                            </div>
                          </div>
                        </div>
                      <?php endif; ?>
                    <?php endforeach; ?>

                    <!-- Invisible spacer for scroll -->
                    <div style="height: 1px;" id="scrollTarget"></div>
                  <?php else: ?>
                    <div class="d-flex flex-column justify-content-center align-items-center h-100">
                      <i class="bi bi-chat-dots mb-3" style="font-size: 3rem; color: #1E2A3A;"></i>
                      <p class="text-center mb-0" style="color: #8A9BB5;">No messages yet</p>
                      <small style="color: #546E8A;">Start the conversation!</small>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Chat Input -->
              <div class="p-3" style="background-color: #141B2B; border-top: 1px solid #1E2A3A;">
                <form action="<?= BASE_URL ?>/?c=customerChat&m=send" method="POST" class="d-flex gap-2 align-items-center">
                  <input type="hidden" name="receiver_id" value="<?= $chatWith['id'] ?>">
                  <div class="flex-grow-1">
                    <input type="text"
                      name="message"
                      class="form-control"
                      placeholder="Type a message..."
                      style="height: 45px; background-color: #0F1625; border: 1px solid #1E2A3A; border-radius: 8px; color: #E5E9F0;"
                      required>
                  </div>
                  <button type="submit" class="btn px-4" style="height: 45px; min-width: 80px; background-color: #2A6DF4; color: #FFFFFF; border: 1px solid #1E4A8A;">
                    <i class="bi bi-send me-1"></i> Send
                  </button>
                </form>
              </div>
            </div>
          <?php else: ?>
            <!-- No Chat Selected -->
            <div class="d-flex flex-column justify-content-center align-items-center h-100">
              <i class="bi bi-chat-dots mb-3" style="font-size: 4rem; color: #1E2A3A;"></i>
              <h5 style="color: #E5E9F0; font-weight: 500;">Select a chat</h5>
              <p style="color: #8A9BB5;" class="text-center px-4">Choose a conversation from the sidebar to start messaging</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</main>

<style>
  /* Container */
  .main-wrapper .container-fluid {
    max-width: 1600px;
    margin: 0 auto;
    height: 100%;
  }

  /* Card Chat */
  .card {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
    height: 100%;
  }

  /* Row & Col */
  .row.g-0 {
    height: 100%;
  }

  [class*="col-"] {
    height: 100%;
  }

  /* Chat List */
  .min-width-0 {
    min-width: 0;
  }

  /* Chat Messages Area */
  #chatBox {
    scroll-behavior: smooth;
    min-height: 0;
    height: 100%;
  }

  #chatBox>div {
    min-height: min-content;
  }

  /* Message Bubbles */
  .rounded-3 {
    border-radius: 12px !important;
  }

  /* Scrollbar */
  .overflow-auto::-webkit-scrollbar {
    width: 5px;
  }

  .overflow-auto::-webkit-scrollbar-track {
    background: #141B2B;
  }

  .overflow-auto::-webkit-scrollbar-thumb {
    background: #2A3A4F;
    border-radius: 3px;
  }

  .overflow-auto::-webkit-scrollbar-thumb:hover {
    background: #3A4F6A;
  }

  /* Form Controls */
  .form-control {
    font-size: 14px;
  }

  .form-control:focus {
    border-color: #2A6DF4;
    box-shadow: 0 0 0 2px rgba(42, 109, 244, 0.2);
    outline: none;
    background-color: #1A2335;
    color: #E5E9F0;
  }

  .form-control::placeholder {
    color: #546E8A;
  }

  /* Button */
  .btn {
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .btn:hover {
    background-color: #1E4A8A !important;
    border-color: #2A6DF4 !important;
  }

  /* Responsive */
  @media (max-width: 767px) {
    .main-wrapper {
      margin-left: 0;
    }

    .main-wrapper .container-fluid {
      padding: 10px !important;
    }

    .col-md-4 {
      position: fixed;
      left: -100%;
      top: 60px;
      bottom: 0;
      width: 100%;
      z-index: 1050;
      transition: left 0.3s ease;
    }

    .col-md-4.show {
      left: 0;
    }
  }

  /* Text colors */
  .text-muted {
    color: #8A9BB5 !important;
  }

  .text-success {
    color: #2A6DF4 !important;
  }

  /* Border colors */
  .border-end {
    border-right: 1px solid #1E2A3A !important;
  }

  .border-bottom {
    border-bottom: 1px solid #1E2A3A !important;
  }

  .border-top {
    border-top: 1px solid #1E2A3A !important;
  }

  /* Hover effects */
  a:hover .p-3 {
    background-color: #1E2A3A !important;
    transition: background-color 0.2s ease;
  }
</style>

<script>
  function toggleSidebar() {
    document.querySelector('.col-md-4').classList.toggle('show');
  }

  function scrollToBottom() {
    const chatBox = document.getElementById('chatBox');
    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
  }

  document.addEventListener('DOMContentLoaded', function() {
    setTimeout(scrollToBottom, 200);

    // Search chat
    const chatSearchInput = document.querySelector('input[placeholder="Search chats..."]');
    if (chatSearchInput) {
      chatSearchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const chatItems = document.querySelectorAll('.chat-link');
        chatItems.forEach(item => {
          const nameElem = item.querySelector('h6');
          const name = nameElem ? nameElem.textContent.toLowerCase() : '';
          item.style.display = name.includes(searchTerm) ? '' : 'none';
        });
      });
    }
  });

  // Auto refresh messages
  <?php if (!empty($chatWith['id'])): ?>
    setInterval(function() {
      fetch('<?= BASE_URL ?>/?c=customerChat&m=getMessages&userId=<?= $chatWith['id'] ?>')
        .then(r => r.text())
        .then(html => {
          const temp = document.createElement('div');
          temp.innerHTML = html;
          const newMessages = temp.querySelector('#chatBox')?.innerHTML;
          const chatBox = document.getElementById('chatBox');
          if (newMessages && chatBox && chatBox.innerHTML !== newMessages) {
            const atBottom = chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 100;
            chatBox.innerHTML = newMessages;
            if (atBottom) setTimeout(scrollToBottom, 100);
          }
        });
    }, 5000);
  <?php endif; ?>

  // Update unread badge
  function updateSidebarBadges() {
    fetch('<?= BASE_URL ?>/?c=customerChat&m=getUnreadPerUser')
      .then(res => res.json())
      .then(data => {
        document.querySelectorAll('.unread-badge').forEach(b => {
          b.style.display = 'none';
          const name = b.closest('.ms-3').querySelector('h6');
          if (name) name.classList.remove('fw-bold');
        });
        data.forEach(item => {
          const badge = document.querySelector(`.unread-badge[data-user-id="${item.sender_id}"]`);
          if (badge) {
            badge.textContent = item.total;
            badge.style.display = 'inline-block';
            const name = badge.closest('.ms-3').querySelector('h6');
            if (name) name.classList.add('fw-bold');
          }
        });
      });
  }

  // Jalankan pertama kali + interval
  updateSidebarBadges();
  setInterval(updateSidebarBadges, 5000);
</script>

<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>