<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>

<main class="main-wrapper">
  <div class="main-content">

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Chat</div>
    </div>

    <div class="card rounded-4">
      <div class="card-body p-0">

        <div class="chat-container">

          <!-- SIDEBAR -->
          <div class="chat-sidebar">
            <h6 class="chat-sidebar-title">Chats</h6>

            <?php foreach ($chats as $chat): ?>
              <a href="?chat_id=<?= $chat['id'] ?>"
                 class="chat-user <?= ($activeChatId == $chat['id']) ? 'active' : '' ?>">
                <?= htmlspecialchars($chat['seller_name']) ?>
              </a>
            <?php endforeach; ?>
          </div>

          <!-- MAIN CHAT -->
          <div class="chat-main">

            <div class="chat-messages">
              <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $msg): ?>
                  <div class="chat-bubble <?= $msg['sender_role'] === 'customer' ? 'me' : 'them' ?>">
                    <?= htmlspecialchars($msg['message']) ?>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="chat-empty">Select a chat to start messaging</div>
              <?php endif; ?>
            </div>

            <?php if (!empty($activeChatId)): ?>
            <form method="POST"
                  action="<?= BASE_URL ?>/?c=customerChat&m=send"
                  class="chat-input">
              <input type="hidden" name="chat_id" value="<?= $activeChatId ?>">
              <input type="text" name="message" placeholder="Type message..." required>
              <button class="btn btn-primary">Send</button>
            </form>
            <?php endif; ?>

          </div>
        </div>

      </div>
    </div>

  </div>
</main>



<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>
