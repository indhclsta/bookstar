<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<!-- start main wrapper -->
<main class="main-wrapper">
    <div class="main-content">

        <!-- breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">FAQ</div>
            <div class="ms-auto"></div>
        </div>
        <!-- end breadcrumb -->

        <div class="row">
            <div class="col-12 col-lg-9 mx-auto">
                <div class="text-center">
                    <h5 class="mb-0 text-uppercase">
                        Frequently Asked Questions (FAQ<small class="text-lowercase">s</small>)
                    </h5>
                    <hr>
                </div>

                <div class="card">
                    <div class="card-body">

                        <div class="accordion" id="accordionCustomerFAQ">

                            <!-- QnA 1 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                        Bagaimana cara membeli buku di BookStar?
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#accordionCustomerFAQ">
                                    <div class="accordion-body">
                                        Pilih buku dari daftar → Tambahkan ke keranjang → Checkout → Upload bukti transfer → Tunggu persetujuan dari penjual.
                                    </div>
                                </div>
                            </div>

                            <!-- QnA 2 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                        Bagaimana cara mengupload bukti transfer?
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#accordionCustomerFAQ">
                                    <div class="accordion-body">
                                        Masuk ke menu Transaksi → Pilih pesanan → Klik Upload Bukti Transfer → Pilih file gambar → Kirim.
                                    </div>
                                </div>
                            </div>

                            <!-- QnA 3 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                        Bagaimana cara melihat status pesanan?
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#accordionCustomerFAQ">
                                    <div class="accordion-body">
                                        Masuk ke menu Transaksi → Status pesanan akan tampil: Menunggu Approve, Ditolak, Diproses, atau Selesai.
                                    </div>
                                </div>
                            </div>

                            <!-- QnA 4 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading4">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                        Bagaimana cara mencetak invoice?
                                    </button>
                                </h2>
                                <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#accordionCustomerFAQ">
                                    <div class="accordion-body">
                                        Masuk ke menu Transaksi → Pilih pesanan → Klik Print Invoice → Cetak atau simpan sebagai PDF.
                                    </div>
                                </div>
                            </div>

                            <!-- QnA 5 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading5">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                                        Bagaimana cara mencari buku tertentu?
                                    </button>
                                </h2>
                                <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#accordionCustomerFAQ">
                                    <div class="accordion-body">
                                        Gunakan kolom pencarian pada halaman buku dengan mengetik nama buku atau kategori.
                                    </div>
                                </div>
                            </div>

                            <!-- QnA 6 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading6">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6">
                                        Apa arti status pesanan Ditolak?
                                    </button>
                                </h2>
                                <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#accordionCustomerFAQ">
                                    <div class="accordion-body">
                                        Status Ditolak berarti bukti pembayaran tidak sesuai. Silakan upload ulang bukti transfer atau hubungi penjual.
                                    </div>
                                </div>
                            </div>

                            <!-- QnA 7 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading7">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7">
                                        Bagaimana cara melihat notifikasi pesanan?
                                    </button>
                                </h2>
                                <div id="collapse7" class="accordion-collapse collapse" data-bs-parent="#accordionCustomerFAQ">
                                    <div class="accordion-body">
                                        Notifikasi akan muncul otomatis ketika pesanan di-approve atau ditolak oleh penjual.
                                    </div>
                                </div>
                            </div>

                            <!-- QnA 8 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading8">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8">
                                        Bagaimana cara menghubungi penjual?
                                    </button>
                                </h2>
                                <div id="collapse8" class="accordion-collapse collapse" data-bs-parent="#accordionCustomerFAQ">
                                    <div class="accordion-body">
                                        Gunakan fitur Chat pada menu Pesanan untuk menghubungi penjual secara langsung.
                                    </div>
                                </div>
                            </div>

                            <!-- QnA 9 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading9">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse9">
                                        Apa arti status online dan offline penjual?
                                    </button>
                                </h2>
                                <div id="collapse9" class="accordion-collapse collapse" data-bs-parent="#accordionCustomerFAQ">
                                    <div class="accordion-body">
                                        Warna hijau menandakan penjual online, sedangkan warna merah menandakan penjual offline.
                                    </div>
                                </div>
                            </div>

                            <!-- QnA 10 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading10">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse10">
                                        Bagaimana cara melihat riwayat transaksi?
                                    </button>
                                </h2>
                                <div id="collapse10" class="accordion-collapse collapse" data-bs-parent="#accordionCustomerFAQ">
                                    <div class="accordion-body">
                                        Masuk ke menu Transaksi untuk melihat semua pesanan yang sedang diproses maupun yang sudah selesai.
                                    </div>
                                </div>
                            </div>

                        </div><!-- accordion -->

                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
<!-- end main wrapper -->

<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>
