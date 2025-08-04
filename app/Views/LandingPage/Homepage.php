<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .rounded-image {
            border-radius: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .video-custom {
            border-radius: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .orange-text {
            color: orange;
        }

        .login-btn {
            background-color: orange;
            border: none;
        }

        .login-btn:hover {
            background-color: darkorange;
        }

        .text-top {
            margin-top: -20px;
        }

        .black-text {
            color: #212529;
        }
    </style>
</head>
<body>

<?= view('layout/navbar') ?>

<!-- Bagian Bawah: Gambar di kiri, Penjelasan di kanan -->
<div class="container py-5">
    <div class="row align-items-center g-5">
        <!-- Kolom kiri: Gambar -->
        <div class="col-md-6 text-center">
            <img src="/img/polban.jpg" alt="Tracer Study" class="img-fluid rounded-image">
        </div>

        <!-- Kolom kanan: Penjelasan Tracer Study -->
        <div class="col-md-6 d-flex flex-column justify-content-start text-top">
            <h2 class="mb-4 black-text">Apa itu Tracer Study?</h2>
            <p>
                Tracer Study adalah survei                                                                                                                                                                                                                                                                       yang dilakukan kepada alumni suatu institusi pendidikan untuk mengetahui sejauh mana lulusan telah terserap di dunia kerja, bidang kerja yang digeluti, serta relevansi antara pendidikan yang diterima dengan pekerjaan mereka sekarang.
            </p>
            <p>
                Tujuannya adalah untuk mengevaluasi kualitas pendidikan dan memperbaiki sistem pembelajaran agar lebih sesuai dengan kebutuhan dunia kerja, sehingga kampus dapat melakukan penyesuaian kurikulum secara tepat sasaran.
            </p>
        </div>
    </div>
</div>

<!-- Bagian Atas: Judul dan Video -->
<div class="container py-5">
    <div class="row align-items-center g-5">
        <!-- Kolom kiri: Judul dan Deskripsi -->
        <div class="col-md-6 text-top">
            <h2 class="mb-4 black-text">Kenapa Tracer Study Penting?</h2>
            <p>
                Artikel ini menampilkan berbagai informasi terkait Politeknik Negeri Bandung (POLBAN), termasuk pengumuman, studi penelusuran alumni, peringkat, pengalaman mahasiswa, dan tutorial pendidikan.
            </p>
            <p>
                POLBAN mendorong alumni untuk berpartisipasi dalam studi penelusuran guna melacak kemajuan karir mereka dan meningkatkan program institusi. Terdapat video profil POLBAN dari tahun 2020, serta diskusi mengenai tantangan dan manfaat belajar di POLBAN.
            </p>
            <a href="<?= base_url('/admin') ?>" class="btn btn-primary login-btn">Login</a>
        </div>

        <!-- Kolom kanan: Video -->
        <div class="col-md-6 text-center">
            <div class="ratio ratio-16x9 video-custom">
                <iframe 
                    src="https://www.youtube.com/embed/dZsTR26OP84" 
                    title="YouTube video"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?= view('layout/footer') ?>
