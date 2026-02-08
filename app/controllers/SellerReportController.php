<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/ReportModel.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;

class SellerReportController
{
    private $reportModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('seller');

        $this->reportModel = new ReportModel();
    }

    public function index()
    {
        $sellerId = $_SESSION['user']['id'];
        $month    = $_GET['month'] ?? null;
        $year     = $_GET['year'] ?? null;

        // Tahun untuk grafik
        $chartYear = $year ?? date('Y');

        // DATA GRAFIK
        $profitChartRaw = $this->reportModel
            ->getMonthlyProfitBySeller($sellerId, $chartYear);

        $months  = [];
        $profits = [];

        foreach ($profitChartRaw as $row) {
            // asumsi query: month = angka (1-12)
            $months[]  = date('F', mktime(0, 0, 0, $row['month'], 1));
            $profits[] = (int) $row['profit'];
        }

        // DATA TABEL
        $reports = $this->reportModel->getSalesReportBySeller(
            $sellerId,
            $month,
            $year
        );

        $totalIncome = $this->reportModel->getTotalIncome($sellerId);

        require APP_PATH . '/views/seller/report.php';
    }


    public function exportPdf()
    {
        $sellerId = $_SESSION['user']['id'];
        $month = $_POST['month'] ?? null;
        $year  = $_POST['year'] ?? null;

        $reports = $this->reportModel->getSalesReportBySeller($sellerId, $month, $year);
        $totalIncome = $this->reportModel->getTotalIncome($sellerId);

        $chartBase64 = $_POST['chart_image'] ?? null;
        $chartImgHtml = '';

        if ($chartBase64) {
            if (str_starts_with($chartBase64, 'data:image/png;base64,')) {
                $chartBase64 = str_replace('data:image/png;base64,', '', $chartBase64);
            }
            $chartImgHtml = '<img src="data:image/png;base64,' . $chartBase64 . '" style="width:100%; margin-bottom:20px;">';
        }

        ob_start();
        require APP_PATH . '/views/seller/report_pdf.php';
        $html = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("laporan-keuntungan.pdf", ["Attachment" => true]);
    }
}
