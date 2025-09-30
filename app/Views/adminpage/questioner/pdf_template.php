<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($questionnaire['title']) ?></title>
    <style>
        @page {
            margin: 2cm 1.5cm;
            @top-center { content: "Kuesioner: <?= esc($questionnaire['title']) ?>"; font-size: 10pt; }
            @bottom-center { content: "Halaman " counter(page) " dari " counter(pages); font-size: 10pt; }
        }
        body {
            font-family: Helvetica, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #333;
        }
        h1 { font-size: 24pt; text-align: center; margin-bottom: 20pt; border-bottom: 2pt solid #ccc; padding-bottom: 10pt; }
        h2 { font-size: 18pt; margin-top: 30pt; border-bottom: 1pt solid #eee; padding-bottom: 5pt; page-break-before: avoid; }
        h3 { font-size: 14pt; margin-top: 20pt; }
        p { margin: 10pt 0; }
        ul, ol { margin-left: 20pt; }
        table { border-collapse: collapse; width: 100%; margin: 10pt 0; }
        th, td { border: 1pt solid #ddd; padding: 8pt; text-align: left; }
        .condition { font-style: italic; color: #666; margin-top: 5pt; border-left: 3pt solid #ccc; padding-left: 10pt; }
        .section-divider { border-top: 1pt dashed #ddd; margin: 20pt 0; }
    </style>
</head>
<body>
    <!-- Header Section -->
    <h1><?= esc($questionnaire['title']) ?></h1>
    <p><strong>Deskripsi:</strong> <?= esc($questionnaire['deskripsi'] ?? 'Tidak ada deskripsi') ?></p>
    <p><strong>Pengumuman:</strong> <?= esc($questionnaire['announcement'] ?? 'Tidak ada pengumuman') ?></p>
    <p><strong>Tanggal Dibuat:</strong> <?= esc($questionnaire['created_at']) ?></p>
    <p><strong>Status:</strong> <?= esc($questionnaire['is_active'] === 'active' ? 'Aktif' : 'Tidak Aktif') ?></p>

    <!-- Content Structure -->
    <?php $pageNum = 1; ?>
    <?php foreach ($structure['pages'] as $page): ?>
        <h2>Halaman <?= $pageNum++ ?>: <?= esc($page['page_title']) ?></h2>
        <?php if (!empty($page['page_description'])): ?>
            <p><?= esc($page['page_description']) ?></p>
        <?php endif; ?>
        <?php if (!empty($page['conditional_logic'])): ?>
            <div class="condition">Kondisional: <?= formatConditions($page['conditional_logic']) ?></div>
        <?php endif; ?>

        <?php $sectionNum = 1; ?>
        <?php foreach ($page['sections'] as $section): ?>
            <h3>Seksi <?= $pageNum-1 ?>.<?= $sectionNum++ ?>: <?= esc($section['section_title']) ?></h3>
            <?php if (!empty($section['section_description'])): ?>
                <p><?= esc($section['section_description']) ?></p>
            <?php endif; ?>
            <?php if (!empty($section['conditional_logic'])): ?>
                <div class="condition">Kondisional: <?= formatConditions($section['conditional_logic']) ?></div>
            <?php endif; ?>

            <?php $questionNum = 1; ?>
            <?php foreach ($section['questions'] as $q): ?>
                <p><strong>Pertanyaan <?= $pageNum-1 ?>.<?= $sectionNum-1 ?>.<?= $questionNum++ ?>: <?= esc($q['question_text']) ?> (Tipe: <?= esc($q['question_type']) ?>)</strong></p>
                <?php if (!empty($q['condition_json'])): ?>
                    <div class="condition">Kondisional: <?= formatConditions($q['condition_json']) ?></div>
                <?php endif; ?>

                <?php switch (strtolower($q['question_type'])): 
                    case 'text':
                    case 'email':
                    case 'number':
                    case 'textarea': ?>
                        <p>Input: [Kolom teks]</p>
                        <?php break;
                    case 'scale': ?>
                        <p>Skala: <?= esc($q['scale_min'] ?? 1) ?> - <?= esc($q['scale_max'] ?? 10) ?>, Min: <?= esc($q['scale_min_label'] ?? 'Min') ?>, Max: <?= esc($q['scale_max_label'] ?? 'Max') ?></p>
                        <?php break;
                    case 'radio':
                    case 'checkbox':
                    case 'dropdown':
                    case 'select': ?>
                        <ol>
                            <?php foreach ($q['options'] ?? [] as $i => $opt): ?>
                                <li><?= esc($opt) ?> = <?= $i + 1 ?></li>
                            <?php endforeach; ?>
                        </ol>
                        <?php break;
                    case 'matrix': ?>
                        <table>
                            <thead>
                                <tr><th></th>
                                    <?php foreach ($q['matrix_columns'] ?? [] as $col): ?>
                                        <th><?= esc($col['column_text']) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($q['matrix_rows'] ?? [] as $row): ?>
                                    <tr>
                                        <td><?= esc($row['row_text']) ?></td>
                                        <?php foreach ($q['matrix_columns'] ?? [] as $col): ?>
                                            <td>[Pilihan]</td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php break;
                    case 'file': ?>
                        <p>Unggah file: Tipe yang diizinkan [pdf, jpg, dll.], Ukuran maks: [5MB]</p>
                        <?php break;
                    case 'user_field': ?>
                        <p>Referensi field profil: <?= esc($q['user_field_name'] ?? 'Tidak ditentukan') ?></p>
                        <?php break;
                    default: ?>
                        <p>[Tipe pertanyaan tidak dikenal]</p>
                <?php endswitch; ?>
                <div class="section-divider"></div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
</body>
</html>