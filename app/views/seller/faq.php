<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

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

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">FAQ</div>
            <div class="ps-3"></div>
            <div class="ms-auto"></div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-12 col-lg-9 mx-auto">
                <div class="text-center">
                    <h5 class="mb-0 text-uppercase">Frequently Asked Questions (FAQs)</h5>
                    <hr>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="accordion" id="accordionFAQ">
                            <?php
                            // QnA Penjual
                            $faqSeller = [
                                ["Bagaimana menambahkan produk baru?", "Masuk ke dashboard Penjual → Menu Produk → Klik Tambah Produk → Isi nama buku, kategori, stok, harga, modal, margin, deskripsi → Simpan."],
                                ["Bagaimana mengedit produk yang sudah ada?", "Menu Produk → Pilih produk → Klik Edit → Ubah data sesuai kebutuhan → Simpan."],
                                ["Bagaimana menghapus produk?", "Produk bisa dihapus jika stok habis (0) → Klik Delete di daftar produk."],
                                ["Bagaimana memproses pesanan dari Pembeli?", "Menu Pesanan → Pilih detail pesanan → Klik Approve jika bukti transfer valid, atau Tolak jika tidak sesuai → Input nomor resi jika sudah dikirim."],
                                ["Bagaimana melihat status pembayaran Pembeli?", "Menu Pesanan → Kolom Status menampilkan: Menunggu Approve, Ditolak, Refund, Selesai."],
                                ["Bagaimana melihat laporan penjualan?", "Menu Laporan → Pilih bulan & tahun → Download laporan → Grafik menampilkan margin & keuntungan."],
                                ["Bagaimana membalas chat dari Pembeli?", "Menu Pesan/Chat → Pilih percakapan → Tulis balasan → Klik Kirim → Pembeli menerima notifikasi real-time."],
                                ["Bagaimana menambahkan kategori produk baru?", "Hanya bisa dilakukan oleh Super Admin. Penjual tidak bisa menambah kategori sendiri."],
                                ["Bagaimana status online/offline ditampilkan?", "Status ditandai dengan warna: hijau = online, merah = offline."],
                                ["Bagaimana menghapus akun saya sendiri?", "Penjual tidak bisa menghapus sendiri. Akun hanya bisa dihapus oleh Super Admin jika status tidak aktif."]
                            ];

                            foreach ($faqSeller as $index => $item):
                                $headingId = "heading" . ($index+1);
                                $collapseId = "collapse" . ($index+1);
                            ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="<?= $headingId ?>">
                                    <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="<?= $collapseId ?>">
                                        <?= $item[0] ?>
                                    </button>
                                </h2>
                                <div id="<?= $collapseId ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" aria-labelledby="<?= $headingId ?>" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        <?= $item[1] ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!--end main wrapper-->

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>
