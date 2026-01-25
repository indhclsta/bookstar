<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<div class="chat-wrapper">

  <!-- SIDEBAR CHAT LIST -->
  <div class="chat-sidebar">
    <div class="chat-list">
      <?php foreach ($chats as $chat): ?>
        <a href="?chat_id=<?= $chat['id'] ?>" class="list-group-item">
          <strong><?= $chat['customer_name'] ?></strong>
        </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- CHAT CONTENT -->
  <div class="chat-content p-3">
    <?php foreach ($messages as $msg): ?>
      <?php if ($msg['sender_role'] === 'seller'): ?>
        <div class="text-end mb-2">
          <span class="badge bg-primary"><?= $msg['message'] ?></span>
        </div>
      <?php else: ?>
        <div class="text-start mb-2">
          <span class="badge bg-light text-dark"><?= $msg['message'] ?></span>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <!-- INPUT -->
  <?php if (!empty($activeChatId)): ?>
  <form method="POST" action="/sellerChat/send" class="p-3">
    <input type="hidden" name="chat_id" value="<?= $activeChatId ?>">
    <div class="input-group">
      <input type="text" name="message" class="form-control" placeholder="Type message...">
      <button class="btn btn-primary">Send</button>
    </div>
  </form>
  <?php endif; ?>

</div>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>
