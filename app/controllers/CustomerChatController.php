<?php
require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/ChatModel.php';
require_once APP_PATH . '/models/UserModel.php';

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

        // Ambil semua seller (bisa semua seller atau yang pernah order)
        $sellers = $this->chatModel->getAllSellers();
        foreach ($sellers as &$seller) {
            $last = $this->chatModel->getLastMessage($customerId, $seller['id']);

            if ($last) {
                $seller['last_message'] = $last['message'];
                $seller['last_sender_id'] = $last['sender_id'];
                $seller['last_time'] = $last['created_at'];
            } else {
                $seller['last_message'] = null;
            }
        }

        $chatWith = ['id' => '', 'name' => 'Select a chat', 'photo' => '', 'status' => 'Offline'];
        $messages = [];

        if (isset($_GET['userId'])) {
            $sellerId = $_GET['userId'];
            $messages = $this->chatModel->getChatWithSeller($customerId, $sellerId);

            // Ambil info seller
            foreach ($sellers as $s) {
                if ($s['id'] == $sellerId) {
                    $chatWith = $s;
                    break;
                }
            }
        }

        if (isset($_GET['userId'])) {
            $this->chatModel->markAsRead($sellerId, $customerId);
        }

        // Ambil foto customer dari database
        $userModel = new UserModel();
        $customerPhoto = $userModel->getUserPhoto($customerId);

        // Simpan di session untuk digunakan di view
        $_SESSION['user']['photo'] = $customerPhoto;

        require APP_PATH . '/views/customer/chat.php';
    }

    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerId = $_SESSION['user']['id'];
            $receiverId = $_POST['receiver_id'] ?? null;
            $message = $_POST['message'] ?? '';

            if (!$receiverId || !$message) {
                header("Location: " . BASE_URL . "/?c=customerChat&m=index");
                exit;
            }

            $data = [
                'sender_id' => $customerId,
                'receiver_id' => $receiverId,
                'message' => $message
            ];

            $this->chatModel->sendMessage($data);
            header("Location: " . BASE_URL . "/?c=customerChat&m=index&userId=" . $receiverId);
            exit;
        }
    }
    public function getMessages()
    {
        $customerId = $_SESSION['user']['id'];
        $sellerId = $_GET['userId'] ?? null;

        if (!$sellerId) {
            exit;
        }

        $messages = $this->chatModel->getChatWithSeller($customerId, $sellerId);

        require APP_PATH . '/views/customer/chat_messages.php';
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
