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
        // Tambahkan last message ke setiap user
        foreach ($chatUsers as &$user) {
            $last = $this->chatModel->getLastMessage($sellerId, $user['id']);

            if ($last) {
                $user['last_message'] = $last['message'];
                $user['last_sender_id'] = $last['sender_id'];
                $user['last_time'] = $last['created_at'];
            } else {
                $user['last_message'] = null;
            }
        }

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
            if ($selectedUserId) {
                $this->chatModel->markAsRead($selectedUserId, $sellerId);
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
    public function getUnreadPerUser()
    {
        $customerId = $_SESSION['user']['id'];

        $data = $this->chatModel->getUnreadCountPerUser($customerId);

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    public function getUnreadCount()
    {
        $customerId = $_SESSION['user']['id'];

        $count = $this->chatModel->getUnreadCount($customerId);

        header('Content-Type: application/json');
        echo json_encode(['total' => $count]);
        exit;
    }
    public function getTotalUnread()
    {
        $userId = $_SESSION['user']['id'];

        $total = $this->chatModel->getTotalUnread($userId);

        echo json_encode(['total' => $total]);
        exit;
    }
}
