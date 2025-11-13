<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/emailtemplate.css') ?>">

<div class="email-template-container">
    <div class="email-template-header">
        <h2 class="email-template-title">Email Templates</h2>
    </div>

    <div class="email-template-card">
        <div class="table-container">
            <table class="email-template-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Judul</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($templates as $template): ?>
                        <tr>
                            <form action="<?= base_url('admin/emailtemplate/update/' . $template['id']) ?>" method="post" class="template-form">
                                <td class="status-cell">
                                    <input type="text" name="status" value="<?= esc($template['status']) ?>" class="form-input status-input" readonly>
                                </td>
                                <td class="subject-cell">
                                    <input type="text" name="subject" value="<?= esc($template['subject']) ?>" class="form-input subject-input">
                                </td>
                                <td class="message-cell">
                                    <div class="message-wrapper">
                                        <textarea name="message" class="form-textarea message-input" rows="3"><?= esc($template['message']) ?></textarea>
                                        <div class="expand-controls">
                                            <button type="button" class="expand-btn" onclick="toggleExpand(this)">
                                                <svg class="expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="action-cell">
                                    <button type="submit" class="update-btn">Update</button>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleExpand(button) {
    const messageWrapper = button.closest('.message-wrapper');
    const textarea = messageWrapper.querySelector('.message-input');
    const icon = button.querySelector('.expand-icon');
    
    if (textarea.classList.contains('expanded')) {
        textarea.classList.remove('expanded');
        textarea.rows = 3;
        icon.style.transform = 'rotate(0deg)';
    } else {
        textarea.classList.add('expanded');
        textarea.rows = 6;
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>

<!-- Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function toggleExpand(button) {
    const messageWrapper = button.closest('.message-wrapper');
    const textarea = messageWrapper.querySelector('.message-input');
    const icon = button.querySelector('.expand-icon');
    
    if (textarea.classList.contains('expanded')) {
        textarea.classList.remove('expanded');
        textarea.rows = 3;
        icon.style.transform = 'rotate(0deg)';
    } else {
        textarea.classList.add('expanded');
        textarea.rows = 6;
        icon.style.transform = 'rotate(180deg)';
    }
}

// ✅ Tampilkan SweetAlert jika ada flashdata sukses
<?php if (session()->getFlashdata('success')): ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '<?= session()->getFlashdata('success') ?>',
    showConfirmButton: false,
    timer: 2000
});
<?php endif; ?>
</script>

<?= $this->endSection() ?>