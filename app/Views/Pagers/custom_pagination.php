<?php if ($pager->links()) : ?>
    <nav aria-label="Page navigation" class="flex justify-center mt-4">
        <ul class="inline-flex items-center -space-x-px">
            <!-- Tombol Prev -->
            <?php if ($pager->getPreviousPage()) : ?>
                <li>
                    <a href="<?= $pager->getPreviousPage() ?>"
                       class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">
                        Prev
                    </a>
                </li>
            <?php endif; ?>

            <!-- Nomor Halaman -->
            <?php foreach ($pager->links() as $link) : ?>
                <li>
                    <a href="<?= $link['uri'] ?>"
                       class="px-3 py-2 leading-tight border border-gray-300 
                              <?= $link['active'] 
                                  ? 'bg-blue-500 text-white hover:bg-blue-600' 
                                  : 'bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
                        <?= $link['title'] ?>
                    </a>
                </li>
            <?php endforeach; ?>

            <!-- Tombol Next -->
            <?php if ($pager->getNextPage()) : ?>
                <li>
                    <a href="<?= $pager->getNextPage() ?>"
                       class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700">
                        Next
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
                