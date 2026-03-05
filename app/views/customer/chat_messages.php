<div id="chatBox">
  <div class="d-flex flex-column" style="min-height: 100%;">
    <?php if (!empty($messages)): ?>
      <?php foreach ($messages as $msg): ?>
        <?php if ($msg['sender_id'] == $_SESSION['user']['id']): ?>
          
          <!-- My Message -->
          <div class="d-flex justify-content-end mb-3">
            <div style="max-width: 70%;">
              <div class="d-flex justify-content-end align-items-center mb-1">
                <span class="small" style="color: #546E8A;">
                  <?= date('g:i A', strtotime($msg['created_at'])) ?>
                </span>
                <h6 class="mb-0 small fw-bold ms-1" style="color: #8A9BB5;">You</h6>
              </div>
              <div class="p-3 rounded-3" style="background-color: #2A6DF4; color: #FFFFFF;">
                <?= nl2br(htmlspecialchars($msg['message'])) ?>
              </div>
            </div>
          </div>

        <?php else: ?>

          <!-- Seller Message -->
          <div class="d-flex mb-3">
            <div style="max-width: 70%;">
              <div class="d-flex align-items-center mb-1">
                <h6 class="mb-0 small fw-bold me-1" style="color: #8A9BB5;">
                  Seller
                </h6>
                <span class="small" style="color: #546E8A;">
                  <?= date('g:i A', strtotime($msg['created_at'])) ?>
                </span>
              </div>
              <div class="p-3 rounded-3" style="background-color: #141B2B; color: #E5E9F0;">
                <?= nl2br(htmlspecialchars($msg['message'])) ?>
              </div>
            </div>
          </div>

        <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>