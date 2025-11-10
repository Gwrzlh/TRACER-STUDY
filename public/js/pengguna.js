document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.row-checkbox');

    // === PILIH SEMUA ===
    if (selectAll) {
        selectAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            if (!cb.checked) selectAll.checked = false;
            else if (document.querySelectorAll('.row-checkbox:checked').length === checkboxes.length) {
                selectAll.checked = true;
            }
        });
    });

    // === HAPUS TERPILIH ===
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');
    if (bulkDeleteForm) {
        bulkDeleteForm.addEventListener('submit', e => {
            const checked = document.querySelectorAll('.row-checkbox:checked');
            if (checked.length === 0) {
                e.preventDefault();
                showAlert('Pilih minimal satu akun untuk dihapus.');
            }
        });
    }

    // === HAPUS TUNGGAL (pakai SweetAlert2) ===
    document.querySelectorAll('.btn-delete-single').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');

            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data pengguna ini akan dihapus permanen.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'post';
                    form.action = `${window.location.origin}/admin/pengguna/delete/${id}`;

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '<?= csrf_token() ?>';
                    csrfInput.value = '<?= csrf_hash() ?>';

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

    // === ALERT CUSTOM ===
    window.showAlert = function (msg) {
        const old = document.getElementById('alert-popup');
        if (old) old.remove();
        const alertBox = document.createElement('div');
        alertBox.id = 'alert-popup';
        alertBox.className = 'alert alert-danger position-fixed top-0 end-0 m-3 shadow-sm';
        alertBox.style.zIndex = '9999';
        alertBox.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>${msg}`;
        document.body.appendChild(alertBox);
        setTimeout(() => alertBox.remove(), 3000);
    };
});
