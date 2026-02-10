<?php

require_once APP_PATH . '/models/NotificationModel.php';
require_once APP_PATH . '/core/auth.php';

class NotificationController
{
    private $notifModel;

    public function __construct()
    {
        Auth::check();
        $this->notifModel = new NotificationModel();
    }

    public function read()
    {
        $id = $_GET['id'] ?? null;
        $redirect = $_GET['redirect'] ?? BASE_URL;

        if ($id) {
            $this->notifModel->markAsRead($id);
        }

        header("Location: " . $redirect);
        exit;
    }
}
