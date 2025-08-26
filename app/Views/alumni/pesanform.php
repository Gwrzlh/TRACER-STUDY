<div class="container mt-4">
    <h3>Kirim Pesan ke <?= esc($penerima['nama'] ?? 'Alumni') ?></h3>

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('alumni/kirimPesanManual') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="id_penerima" value="<?= $penerima['id'] ?>">

        <!-- <div class="form-group mb-3">
            <label for="subject">Subjek</label>
            <input type="text" id="subject" name="subject" class="form-control" required>
        </div> -->

        <div class="form-group mb-3">
            <label for="message">Pesan</label>
            <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-send"></i> Kirim
        </button>
    </form>
</div>