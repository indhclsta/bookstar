<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/ChatModel.php';

class SellerChatController
{
    private $chatModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('seller');

        $this->chatModel = new ChatModel();
    }

    public function index()
    {
        $sellerId = $_SESSION['user']['id'];

        // LIST CHAT (sidebar)
        $chats = $this->chatModel->getChatsBySeller($sellerId);

        // CHAT AKTIF
        $activeChatId = $_GET['chat_id'] ?? null;
        $messages = [];

        if ($activeChatId) {
            $messages = $this->chatModel->getMessages($activeChatId);
        }

        require APP_PATH . '/views/seller/chat.php';
    }
}
