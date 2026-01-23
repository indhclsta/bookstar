<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/ReportModel.php';

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
        $month = (int)($_GET['month'] ?? date('n'));
        $year  = (int)($_GET['year'] ?? date('Y'));

        // ditolak tidak ditampilkan
        $summary = $this->reportModel->getSummary($sellerId, $month, $year);
        $table   = $this->reportModel->getTable($sellerId, $month, $year);
        $chart   = $this->reportModel->getChartDaily($sellerId, $month, $year);

        require APP_PATH . '/views/seller/report/index.php';
    }

    public function download()
    {
        // optional: download csv
        $sellerId = $_SESSION['user']['id'];
        $month = (int)($_GET['month'] ?? date('n'));
        $year  = (int)($_GET['year'] ?? date('Y'));

        $rows = $this->reportModel->getTable($sellerId, $month, $year);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="laporan_'.$month.'_'.$year.'.csv"');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['Tanggal', 'Kode Pesanan', 'Judul', 'Qty', 'Total', 'Metode', 'Status']);
        foreach ($rows as $r) {
            fputcsv($out, [
                $r['created_at'],
                $r['order_code'],
                $r['title'],
                $r['qty'],
                $r['total'],
                $r['payment_method'],
                $r['status'],
            ]);
        }
        fclose($out);
        exit;
    }
}
