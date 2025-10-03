<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Jawaban Alumni</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 18px;
            color: #222;
        }

        .page-title {
            margin: 15px 0 8px;
            font-size: 15px;
            font-weight: bold;
            color: #444;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
        }

        .section-title {
            margin: 10px 0 6px;
            font-size: 13px;
            font-style: italic;
            color: #555;
        }

        .question-block {
            margin-bottom: 12px;
        }

        .question {
            font-weight: bold;
            color: #000;
        }

        .answer {
            margin-top: 3px;
            padding: 6px 10px;
            background: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .no-answer {
            font-style: italic;
            color: #999;
        }
    </style>
</head>

<body>
    <h2>Jawaban dari <?= esc($nama) ?></h2>
    <p><strong>Jurusan</strong> <?= esc($jurusan) ?></p>
    <p><strong>Program Studi</strong> <?= esc($prodi) ?></p>
    <hr>


    <?php if (!empty($structure['pages'])): ?>
        <?php foreach ($structure['pages'] as $page): ?>
            <div class="page-title"><?= esc($page['page_title'] ?? 'Halaman') ?></div>

            <?php if (!empty($page['sections'])): ?>
                <?php foreach ($page['sections'] as $section): ?>
                    <?php if (!empty($section['show_section_title'])): ?>
                        <div class="section-title"><?= esc($section['section_title']) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($section['questions'])): ?>
                        <?php foreach ($section['questions'] as $q): ?>
                            <?php
                            $key     = 'q_' . $q['id'];
                            $answer  = $answers[$key] ?? '';
                            $decoded = json_decode($answer, true);
                            $answersArr = is_array($decoded) ? $decoded : (strlen($answer) ? [$answer] : []);
                            ?>
                            <div class="question-block">
                                <div class="question"><?= esc($q['question_text']) ?></div>
                                <div class="answer">
                                    <?php if (in_array(strtolower($q['question_type']), ['text', 'email', 'dropdown', 'select', 'radio', 'scale', 'matrix_scale'])): ?>
                                        <?= $answer ? esc($answer) : '<span class="no-answer">Belum dijawab</span>' ?>
                                    <?php elseif (strtolower($q['question_type']) === 'checkbox'): ?>
                                        <?= !empty($answersArr) ? esc(implode(', ', $answersArr)) : '<span class="no-answer">Belum dijawab</span>' ?>
                                    <?php elseif (strtolower($q['question_type']) === 'file'): ?>
                                        <?= $answer ? 'File diunggah: ' . esc(basename($answer)) : '<span class="no-answer">Belum ada file</span>' ?>
                                    <?php else: ?>
                                        <?= $answer ? esc($answer) : '<span class="no-answer">Belum dijawab</span>' ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</body>

</html>