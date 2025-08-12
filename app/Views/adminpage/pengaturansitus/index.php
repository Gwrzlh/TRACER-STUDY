<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<style>
    .settings-container {
        max-width: 1000px;
        margin: 0;
        padding: 0 20px;
    }

    .settings-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin: 0;
        border: 1px solid #e0e4e8;
    }

    .settings-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f1f3f4;
    }

    .settings-header i {
        font-size: 2rem;
        color: #4285f4;
        margin-right: 15px;
    }

    .settings-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .settings-subtitle {
        color: #666;
        margin: 5px 0 0 0;
        font-size: 1rem;
    }

    .section-group {
        margin-bottom: 35px;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 25px;
        border-left: 4px solid #4285f4;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 10px;
        color: #4285f4;
        font-size: 1.2rem;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e1e5e9;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background-color: #fff;
    }

    .form-control:focus {
        border-color: #4285f4;
        box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.1);
        outline: none;
    }

    .form-control:hover {
        border-color: #bdc3c7;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 60px;
    }

    .btn-primary {
        background-color: orange;
        border-color: orange;
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover {
        background-color: #e6850e;
        border-color: #e6850e;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 165, 0, 0.3);
    }

    .btn-primary i {
        font-size: 0.9rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
        z-index: 2;
    }

    .input-with-icon .form-control {
        padding-left: 45px;
    }

    .form-help {
        font-size: 0.85rem;
        color: #666;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-help i {
        color: #4285f4;
    }

    @media (max-width: 768px) {
        .settings-container {
            padding: 0 15px;
        }

        .settings-card {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .settings-header {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }

        .settings-header i {
            margin-right: 0;
            margin-bottom: 10px;
        }
    }

    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #28a745;
        margin-bottom: 20px;
        display: none;
    }
</style>

<div class="settings-container">
    <div class="settings-card">
        <div class="settings-header">
            <i class="fas fa-cogs"></i>
            <div>
                <h1 class="settings-title">Pengaturan Situs</h1>
                <p class="settings-subtitle">Kelola informasi utama situs Tracer Study POLBAN</p>
            </div>
        </div>

        <div class="success-message" id="successMessage">
            <i class="fas fa-check-circle"></i>
            Pengaturan berhasil disimpan!
        </div>

        <form action="/pengaturansitus/simpan" method="post" id="settingsForm">
            <!-- Informasi Situs -->
            <div class="section-group">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Situs
                </h3>
                
                <div class="form-group">
                    <label class="form-label" for="judul">Judul Situs</label>
                    <div class="input-with-icon">
                        <i class="fas fa-heading"></i>
                        <input type="text" 
                               name="judul" 
                               id="judul"
                               value="Tracer Study POLBAN" 
                               class="form-control"
                               placeholder="Masukkan judul situs">
                    </div>
                    <div class="form-help">
                        <i class="fas fa-lightbulb"></i>
                        Judul ini akan muncul di header website
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="deskripsi">Deskripsi Situs</label>
                    <textarea name="deskripsi" 
                              id="deskripsi"
                              class="form-control" 
                              rows="4"
                              placeholder="Masukkan deskripsi situs...">Tracer Study POLBAN membantu evaluasi kualitas pendidikan dan kesiapan lulusan dalam dunia kerja melalui survei berkala kepada alumni.</textarea>
                    <div class="form-help">
                        <i class="fas fa-lightbulb"></i>
                        Deskripsi singkat tentang tujuan situs ini
                    </div>
                </div>
            </div>

            <!-- Navigasi Utama -->
            <div class="section-group">
                <h3 class="section-title">
                    <i class="fas fa-sitemap"></i>
                    Navigasi Utama
                </h3>
                
                <div class="form-group">
                    <label class="form-label" for="navigasi">Menu Navigasi</label>
                    <div class="input-with-icon">
                        <i class="fas fa-bars"></i>
                        <input type="text" 
                               name="navigasi" 
                               id="navigasi"
                               value="Tentang, Kontak, Respon TS, Laporan TS" 
                               class="form-control"
                               placeholder="Menu1, Menu2, Menu3...">
                    </div>
                    <div class="form-help">
                        <i class="fas fa-lightbulb"></i>
                        Pisahkan setiap menu dengan koma
                    </div>
                </div>
            </div>

            <!-- Sistem Teknis -->
            <div class="section-group">
                <h3 class="section-title">
                    <i class="fas fa-cog"></i>
                    Sistem Teknis
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="penyimpanan">Opsi Penyimpanan</label>
                        <div class="input-with-icon">
                            <i class="fas fa-save"></i>
                            <input type="text" 
                                   name="penyimpanan" 
                                   id="penyimpanan"
                                   value="Simpan saat klik tombol LANJUTKAN" 
                                   class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="validasi_email">Validasi Email</label>
                        <div class="input-with-icon">
                            <i class="fas fa-envelope-check"></i>
                            <input type="text" 
                                   name="validasi_email" 
                                   id="validasi_email"
                                   value="Harus mengandung @, tanpa spasi" 
                                   class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Akses Koordinator -->
            <div class="section-group">
                <h3 class="section-title">
                    <i class="fas fa-user-tie"></i>
                    Akses Koordinator
                </h3>
                
                <div class="form-group">
                    <label class="form-label" for="akses_koordinator">Ketentuan Akses</label>
                    <textarea name="akses_koordinator" 
                              id="akses_koordinator"
                              class="form-control" 
                              rows="3"
                              placeholder="Jelaskan ketentuan akses untuk koordinator...">Akses data hasil survey melalui akun polban.ac.id dengan otoritas penuh untuk melihat, menganalisis, dan mengunduh laporan tracer study.</textarea>
                    <div class="form-help">
                        <i class="fas fa-lightbulb"></i>
                        Atur hak akses untuk koordinator tracer study
                    </div>
                </div>
            </div>

            <!-- Platform Teknis -->
            <div class="section-group">
                <h3 class="section-title">
                    <i class="fas fa-code"></i>
                    Platform Teknis
                </h3>
                
                <div class="form-group">
                    <label class="form-label" for="platform">Informasi Platform</label>
                    <textarea name="platform" 
                              id="platform"
                              class="form-control" 
                              rows="3"
                              placeholder="Informasi teknis platform...">Dibangun dengan tracer.id, menggunakan framework modern untuk kemudahan akses dan analisis data. Lisensi CC-BY-NC-SA 4.0 untuk penggunaan non-komersial.</textarea>
                    <div class="form-help">
                        <i class="fas fa-lightbulb"></i>
                        Detail teknis dan lisensi platform
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 40px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Simulasi penyimpanan
    const successMessage = document.getElementById('successMessage');
    successMessage.style.display = 'block';
    
    // Scroll ke pesan sukses
    successMessage.scrollIntoView({ behavior: 'smooth' });
    
    // Sembunyikan pesan setelah 3 detik
    setTimeout(() => {
        successMessage.style.display = 'none';
    }, 3000);
    
    // Di implementasi nyata, hapus preventDefault dan biarkan form submit normal
    // this.submit();
});

// Auto-resize textarea
document.querySelectorAll('textarea').forEach(textarea => {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>

<?= $this->endSection() ?>