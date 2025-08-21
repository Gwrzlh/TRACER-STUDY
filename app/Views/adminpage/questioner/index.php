<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('css/questioner/index.css') ?>">
<h2>Daftar Kuesioner</h2>
<a href="<?= base_url('admin/questionnaire/create') ?>">+ Buat Kuesioner Baru</a>
<table border="1" cellpadding="5">
    <tr>
        <th>Judul</th>
        <th>Deskripsi</th>
        <th>Conditional</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php foreach($questionnaires as $q): ?>
    <tr>
        <td><?= esc($q['title']) ?></td>
        <td><?= esc($q['deskripsi']) ?></td>
        <td><?= esc($q['conditional_logic']) ?></td>
        <td><?= esc($q['is_active']) ?></td>
        <td>
            <a href="<?= base_url('admin/questionnaire/' . $q['id'] . '/pages') ?>">Kelola Halaman</a> 
            | 
            <a href="<?= base_url('admin/questionnaire/' . $q['id'] . '/edit') ?>">Edit</a>
            | 
           <!-- Tombol Hapus -->
            <button class="btn btn-danger btn-sm delete-questionnaire" 
                    data-id="<?= $q['id'] ?>">
                <i class="fas fa-trash"></i> Hapus
            </button>

        </td>
    </tr>
    <?php endforeach; ?>
</table>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const baseUrl = "<?= base_url('admin/questionnaire') ?>";

    document.querySelectorAll(".delete-questionnaire").forEach(button => {
        button.addEventListener("click", function() {
            const id = this.getAttribute("data-id");

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data questionnaire ini beserta halaman, section, dan pertanyaan terkait akan terhapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `${baseUrl}/${id}/delete`;
                }
            });
        });
    });
});
</script>


<?= $this->endSection() ?>
