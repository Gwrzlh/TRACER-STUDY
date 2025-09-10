<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="p-6 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">Email Templates</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">Status</th>
                <th class="border px-4 py-2">Judul</th>
                <th class="border px-4 py-2">Message</th>
                <th class="border px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($templates as $template): ?>
                <tr>
                    <form action="<?= base_url('admin/emailtemplate/update/' . $template['id']) ?>" method="post">
                        <td class="border px-4 py-2">
                            <input type="text" name="status" value="<?= esc($template['status']) ?>" class="w-full border rounded px-2 py-1">
                        </td>
                        <td class="border px-4 py-2">
                            <input type="text" name="subject" value="<?= esc($template['subject']) ?>" class="w-full border rounded px-2 py-1">
                        </td>
                        <td class="border px-4 py-2">
                            <textarea name="message" class="w-full border rounded px-2 py-1" rows="3"><?= esc($template['message']) ?></textarea>
                        </td>
                        <td class="border px-4 py-2 text-center">
                            <button type="submit" class="px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>