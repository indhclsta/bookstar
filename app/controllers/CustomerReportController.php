<?php
require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/ReportModel.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;

class CustomerReportController
{
    private $reportModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('customer');
        $this->reportModel = new ReportModel();
    }

    public function index()
{
    $customerId = $_SESSION['user']['id'];

    // ambil filter dari URL
    $month = $_GET['month'] ?? null;
    $year  = $_GET['year'] ?? date('Y');

    // laporan tabel
    $reports = $this->reportModel->getPurchaseReport(
        $customerId,
        $month,
        $year
    );

    // data chart
    $chart = $this->reportModel->getMonthlyPurchaseChart(
        $customerId,
        $year
    );

    require APP_PATH . '/views/customer/report_purchase.php';
}


    // 👉 EXPORT PDF
    public function exportPdf()
    {
        $customerId = $_SESSION['user']['id'];
        $reports = $this->reportModel->getPurchaseReport($customerId);

        ob_start();
        require APP_PATH . '/views/customer/report_purchase_pdf.php';
        $html = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("laporan_pembelian.pdf", ["Attachment" => true]);
    }
}
