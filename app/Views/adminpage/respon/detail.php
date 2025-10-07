<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<link href="<?= base_url('css/respon/detail.css') ?>" rel="stylesheet">

<div class="container mt-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>🧾 Detail Jawaban Alumni</h3>
        <div>
            <a href="<?= base_url('admin/respon') ?>" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <form method="post" action="<?= base_url('admin/respon/saveFlags') ?>">
        <?php foreach ($structure['pages'] as $page): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white"><?= esc($page['page_title']) ?></div>
                <div class="card-body">
                    <?php foreach ($page['sections'] as $section): ?>
                        <?php if ($section['show_section_title']): ?>
                            <h6 class="text-muted mb-3"><?= esc($section['section_title']) ?></h6>
                        <?php endif; ?>
                        <?php foreach ($section['questions'] as $q): ?>
                            <div class="question-item border-bottom pb-2 mb-3 d-flex justify-content-between align-items-start">
                                <div style="flex: 1;">
                                    <strong><?= esc($q['question_text']) ?></strong>
                                    <?php
                                    $key = 'q_' . $q['id'];
                                    $answer = $answers[$key] ?? '';
                                    $decoded = json_decode($answer, true);
                                    $answersArr = is_array($decoded) ? $decoded : (strlen($answer) ? [$answer] : []);
                                    ?>
                                    <p class="mt-1 mb-0 text-secondary">
                                        <?= $answer ? esc(is_array($answersArr) ? implode(', ', $answersArr) : $answer) : '<em>Belum dijawab</em>' ?>
                                    </p>
                                </div>

                                <div class="text-end" style="min-width: 160px;">
                                    <div class="form-check">
                                        <input type="checkbox" name="akreditasi[]" value="<?= $q['id'] ?>" class="form-check-input" <?= !empty($q['is_for_accreditation']) ? 'checked' : '' ?>>
                                        <label class="form-check-label">Akreditasi</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="ami[]" value="<?= $q['id'] ?>" class="form-check-input" <?= !empty($q['is_for_ami']) ? 'checked' : '' ?>>
                                        <label class="form-check-label">AMI</label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="text-end mb-5">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Simpan</button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>