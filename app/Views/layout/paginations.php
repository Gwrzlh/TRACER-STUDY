<?php if ($pager) : ?>
    <nav aria-label="Page navigation" style="margin-top: 10px;">
        <ul class="pagination justify-content-start" style="margin: 0;">
            <!-- Tombol Sebelumnya -->
            <?php if ($pager->hasPreviousPage()) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getPreviousPage() ?>" style="color:#001BB7;">&laquo;</a>
                </li>
            <?php else : ?>
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
            <?php endif; ?>

            <!-- Nomor Halaman -->
            <?php foreach ($pager->links() as $link) : ?>
                <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                    <a class="page-link"
                       href="<?= $link['uri'] ?>"
                       style="<?= $link['active']
                                ? 'background-color:#001BB7; border-color:#001BB7; color:white;'
                                : 'color:#001BB7;' ?>">
                        <?= $link['title'] ?>
                    </a>
                </li>
            <?php endforeach; ?>

            <!-- Tombol Selanjutnya -->
            <?php if ($pager->hasNextPage()) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getNextPage() ?>" style="color:#001BB7;">&raquo;</a>
                </li>
            <?php else : ?>
                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
