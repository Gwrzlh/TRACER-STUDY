<?= $this->extend('layout/sidebar_atasan') ?>
<?= $this->section('content') ?>

<h2>Pengumuman: <?= $questionnaire_title ?></h2>
<div>
    <?= $announcement_content ?>
</div>

<a href="<?= base_url('atasan/kuesioner/mulai/'.$q_id) ?>">Lanjut Isi Kuesioner</a>

<?= $this->endSection() ?>
