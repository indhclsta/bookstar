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

        // Ambil semua chat yang melibatkan seller
        $chats = $this->chatModel->getChatsBySeller($sellerId);

        // Ambil customer yang pernah beli produk seller
        $chatUsers = $this->chatModel->getCustomersByOrders($sellerId);

        // Tentukan chat dengan siapa (default)
        $chatWith = ['name' => 'Select a chat', 'id' => '', 'photo' => '', 'status' => 'Offline'];
        $messages = [];

        // Jika userId dipilih dari sidebar
        $selectedUserId = $_GET['userId'] ?? null;
        if ($selectedUserId) {
            $chatWithUser = array_filter($chatUsers, fn($u) => $u['id'] == $selectedUserId);
            if ($chatWithUser) {
                $chatWith = array_values($chatWithUser)[0];
                $messages = $this->chatModel->getChatWithUser($sellerId, $chatWith['id']);
            }
        }

        require APP_PATH . '/views/seller/chat.php';
    }



    // Kirim pesan
    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sellerId = $_SESSION['user']['id'];
            $receiverId = $_POST['receiver_id'] ?? null;
            $message = $_POST['message'] ?? '';

            if (!$receiverId || !$message) {
                header("Location: " . BASE_URL . "/?c=sellerChat&m=index");
                exit;
            }

            $data = [
                'sender_id' => $sellerId,
                'receiver_id' => $receiverId,
                'message' => $message
            ];

            $this->chatModel->sendMessage($data);
            header("Location: " . BASE_URL . "/?c=sellerChat&m=index&userId=" . $receiverId);
            exit;
        }
    }
}
