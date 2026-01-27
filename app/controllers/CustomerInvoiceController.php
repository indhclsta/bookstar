<?php

require_once APP_PATH . '/models/OrderModel.php';
require_once APP_PATH . '/core/auth.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class CustomerInvoiceController
{
    private $orderModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('customer');

        $this->orderModel = new OrderModel();
    }

    // EXPORT PDF
    public function pdf()
    {
        $checkoutCode = $_GET['checkout'] ?? null;
        if (!$checkoutCode) die('Invalid checkout code');

        $userId = $_SESSION['user']['id'];

        // Ambil data order berdasarkan checkout
        $orders = $this->orderModel->getInvoiceByCheckout($checkoutCode, $userId);

        if (!$orders) die('Invoice not found');

        // Render view PDF ke buffer
        ob_start();
        require APP_PATH . '/views/customer/invoicepdf.php';
        $html = ob_get_clean();

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Invoice-' . $checkoutCode . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }
}
