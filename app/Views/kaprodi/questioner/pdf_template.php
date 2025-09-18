<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pertanyaan Kuesioner #<?= $idKuesioner ?></title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            line-height: 1.5;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        h3 {
            margin-top: 25px;
            margin-bottom: 5px;
            color: #333;
        }

        h4 {
            margin-top: 15px;
            margin-bottom: 5px;
            color: #555;
        }

        p {
            margin-left: 20px;
            margin-bottom: 5px;
        }

        .question-no {
            font-weight: bold;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <h2>Daftar Pertanyaan Kuesioner</h2>

    <?php
    $no = 1;
    foreach ($pages as $page): ?>
        <h3>Halaman: <?= esc($page['title'] ?? 'Halaman ' . $page['order_no']) ?></h3>

        <?php if (!empty($page['sections'])): ?>
            <?php foreach ($page['sections'] as $section): ?>
                <h4>Section: <?= esc($section['section_title']) ?></h4>

                <?php if (!empty($section['questions'])): ?>
                    <?php foreach ($section['questions'] as $q): ?>
                        <p>
                            <span class="question-no"><?= $no++ ?>.</span>
                            <?= esc($q['question_text']) ?>
                        </p>
                    <?php endforeach ?>
                <?php else: ?>
                    <p>Tidak ada pertanyaan di section ini.</p>
                <?php endif; ?>

            <?php endforeach ?>
        <?php else: ?>
            <p>Tidak ada section di halaman ini.</p>
        <?php endif; ?>

    <?php endforeach; ?>
</body>

</html>