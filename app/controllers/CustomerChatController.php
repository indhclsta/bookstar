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

        // Ambil semua seller (bisa semua seller atau yang pernah order)
        $sellers = $this->chatModel->getAllSellers();

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

        // Ambil foto customer dari database
        require_once APP_PATH . '/models/UserModel.php';
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
}