<!-- app/Views/kaprodi/kuesioner/_breadcrumb.php -->
<nav aria-label="breadcrumb" class="mb-3 p-2 bg-white">
    <ol class="breadcrumb-custom">
        <?php
        $segments   = current_url(true)->getSegments();
        $q_id       = $segments[2] ?? null;
        $page_id    = $segments[4] ?? null;
        $section_id = $segments[6] ?? null;
        $is_home    = uri_string() === 'kaprodi/kuesioner';
        ?>

        <!-- Homepage -->
        <li class="<?= $is_home ? 'active' : '' ?>">
            <a href="<?= base_url('kaprodi/kuesioner') ?>">Homepage</a>
        </li>

        <!-- Pages -->
        <?php if ($q_id): ?>
            <li class="<?= ($segments[3] ?? null) === 'pages' && !$page_id ? 'active' : '' ?>">
                <a href="<?= base_url("kaprodi/kuesioner/$q_id/pages") ?>">Pages</a>
            </li>
        <?php endif; ?>

        <!-- Section -->
        <?php if ($page_id): ?>
            <li class="<?= ($segments[5] ?? null) === 'sections' && !$section_id ? 'active' : '' ?>">
                <a href="<?= base_url("kaprodi/kuesioner/$q_id/pages/$page_id/sections") ?>">Section</a>
            </li>
        <?php endif; ?>

        <!-- Question -->
        <?php if ($section_id && ($segments[7] ?? null) === 'questions'): ?>
            <li class="active">
                <a href="<?= base_url("kaprodi/kuesioner/$q_id/pages/$page_id/sections/$section_id/questions") ?>">Question</a>
            </li>
        <?php endif; ?>
    </ol>
</nav>

<style>
    /* Breadcrumb Custom Style */
    .breadcrumb-custom {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 16px;
    }

    .breadcrumb-custom li {
        display: flex;
        align-items: center;
        font-weight: 600;
    }

    /* Separator */
    .breadcrumb-custom li+li::before {
        content: ">";
        margin: 0 12px;
        color: #9ca3af;
        font-weight: normal;
    }

    /* Default link abu-abu */
    .breadcrumb-custom a {
        text-decoration: none;
        color: #6b7280;
        /* abu-abu */
        transition: color 0.2s;
    }

    /* Aktif biru */
    .breadcrumb-custom li.active a {
        color: #4F46E5 !important;
        /* biru */
        font-weight: 700;
    }
</style>