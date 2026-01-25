<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/ChatModel.php';

class CustomerChatController
{
    private $chatModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('customer');

        $this->chatModel = new ChatModel();
    }

    public function index()
    {
        $customerId = $_SESSION['user']['id'];

        // list chat customer
        $chats = $this->chatModel->getChatsByCustomer($customerId);

        $activeChatId = $_GET['chat_id'] ?? null;
        $messages = [];

        if ($activeChatId) {
            $messages = $this->chatModel->getMessagesByCustomer(
                $activeChatId,
                $customerId
            );
        }

        require APP_PATH . '/views/customer/chat.php';
    }

    public function start()
    {
        $customerId = $_SESSION['user']['id'];
        $sellerId   = $_GET['seller_id'];

        // cek chat sudah ada atau belum
        $chatId = $this->chatModel->getOrCreateChat($sellerId, $customerId);

        header("Location: " . BASE_URL . "/?c=customerChat&m=index&chat_id=$chatId");
        exit;
    }

    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die('Invalid request');

        $chatId  = $_POST['chat_id'];
        $message = trim($_POST['message']);
        $userId  = $_SESSION['user']['id'];

        if ($message !== '') {
            $this->chatModel->sendMessage(
                $chatId,
                'customer',
                $userId,
                $message
            );
        }

        header("Location: " . BASE_URL . "/?c=customerChat&m=index&chat_id=$chatId");
        exit;
    }
}
