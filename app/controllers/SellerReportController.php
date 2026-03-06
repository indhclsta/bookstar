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

        $chartYear = $year ?? date('Y');

        // ==============================
        // DATA CHART SESUAI FILTER
        // ==============================

        $months  = [];
        $profits = [];

        if ($month) {

            // Jika filter bulan dipilih
            $profit = $this->reportModel->getProfitByMonth(
                $sellerId,
                $month,
                $chartYear
            );

            $months[]  = date('F', mktime(0, 0, 0, $month, 1));
            $profits[] = (int) ($profit['profit'] ?? 0);
        } else {

            // Jika hanya filter tahun / tanpa filter
            $profitChartRaw = $this->reportModel
                ->getMonthlyProfitBySeller($sellerId, $chartYear);

            foreach ($profitChartRaw as $row) {

                $months[]  = date('F', mktime(0, 0, 0, $row['month'], 1));
                $profits[] = (int) $row['profit'];
            }
        }

        // ==============================
        // DATA TABEL
        // ==============================

        $reports = $this->reportModel->getSalesReportBySeller(
            $sellerId,
            $month,
            $year
        );

        // Total income mengikuti filter juga
        $totalIncome = array_sum(array_column($reports, 'total_penjualan'));

        require APP_PATH . '/views/seller/report.php';
    }

    public function exportPdf()
    {
        $sellerId = $_SESSION['user']['id'];
        $month = $_POST['month'] ?? null;
        $year  = $_POST['year'] ?? null;

        $reports = $this->reportModel->getSalesReportBySeller($sellerId, $month, $year);
        $totalIncome = $this->reportModel->getTotalIncome(
            $sellerId,
            $month,
            $year
        );

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
