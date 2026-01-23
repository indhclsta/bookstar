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
        $threads = $this->chatModel->getThreadsForSeller($sellerId);
        require APP_PATH . '/views/seller/chat/index.php';
    }

    public function detail()
    {
        $sellerId = $_SESSION['user']['id'];
        $buyerId  = (int)($_GET['buyer_id'] ?? 0);
        if ($buyerId <= 0) die('Invalid');

        $messages = $this->chatModel->getMessages($sellerId, $buyerId);

        // tandai pesan buyer sebagai read
        $this->chatModel->markReadForSeller($sellerId, $buyerId);

        require APP_PATH . '/views/seller/chat/detail.php';
    }

    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die('Invalid request');

        $sellerId = $_SESSION['user']['id'];
        $buyerId  = (int)($_POST['buyer_id'] ?? 0);
        $message  = trim($_POST['message'] ?? '');

        if ($buyerId <= 0 || $message === '') {
            $_SESSION['error'] = 'Pesan tidak boleh kosong';
            header('Location: ' . BASE_URL . '/?c=sellerChat&m=index');
            exit;
        }

        $this->chatModel->sendSellerMessage($sellerId, $buyerId, $message);

        header('Location: ' . BASE_URL . '/?c=sellerChat&m=detail&buyer_id=' . $buyerId);
        exit;
    }
}
