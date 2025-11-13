document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("toggleForm");
    const cancelBtn = document.getElementById("cancelForm");
    const formContainer = document.getElementById("formContainer");
    const form = document.getElementById("questionForm");
    const typeSelect = document.getElementById("question_type");
    const optionList = document.getElementById("option_list");

    // =====================
    // Toggle form
    // =====================
    toggleBtn?.addEventListener("click", () => formContainer.classList.toggle("hidden"));
    cancelBtn?.addEventListener("click", () => formContainer.classList.add("hidden"));

    // =====================
    // Dynamic field visibility
    // =====================
    typeSelect?.addEventListener("change", function() {
        const type = this.value;
        document.querySelectorAll(".options-wrapper, .scale-wrapper, .file-wrapper, .matrix-wrapper, .user-field-wrapper")
            .forEach(el => el.classList.add("hidden"));

        if (["radio", "checkbox", "dropdown"].includes(type)) document.getElementById("options_wrapper")?.classList.remove("hidden");
        if (type === "scale") document.getElementById("scale_wrapper")?.classList.remove("hidden");
        if (type === "file") document.getElementById("file_wrapper")?.classList.remove("hidden");
        if (type === "matrix") document.getElementById("matrix_wrapper")?.classList.remove("hidden");
        if (type === "user_field") document.getElementById("user_field_wrapper")?.classList.remove("hidden");
    });

    // =====================
    // Add / Remove option dynamically
    // =====================
    document.getElementById("add_option")?.addEventListener("click", () => {
        const div = document.createElement("div");
        div.classList.add("option-item");
        div.innerHTML = `
            <input type="text" name="options[]" class="form-control" placeholder="Option text...">
            <input type="text" name="option_values[]" class="form-control" placeholder="Value (optional)">
            <button type="button" class="btn btn-danger btn-icon remove-option"><i class="fas fa-times"></i></button>
        `;
        optionList.appendChild(div);
    });

    document.addEventListener("click", e => {
        const removeBtn = e.target.closest(".remove-option");
        if (removeBtn) removeBtn.closest(".option-item")?.remove();
    });

    // =====================
    // Submit form (Add / Edit)
    // =====================
    form?.addEventListener("submit", async function(e) {
        e.preventDefault();
        const fd = new FormData(this);

        try {
            const res = await fetch(this.action, {
                method: "POST",
                body: fd,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });

            const data = await res.json();

            if (data.status === "success") {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: data.message || "Pertanyaan berhasil disimpan"
                }).then(() => location.reload());
            } else {
                // Jika message berupa objek (misal validasi), tampilkan rapi
                let errorMsg = "";
                if (typeof data.message === "string") {
                    errorMsg = data.message;
                } else if (typeof data.message === "object") {
                    errorMsg = Object.values(data.message).flat().join("\n");
                }
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: errorMsg || "Terjadi kesalahan"
                });
            }
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Respon server tidak valid."
            });
            console.error(err);
        }
    });

    // =====================
    // Hapus pertanyaan
    // =====================
    document.addEventListener("click", async function (e) {
        const btn = e.target.closest(".delete-question");
        if (!btn) return;

        Swal.fire({
            title: "Yakin hapus?",
            text: "Data ini tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus"
        }).then(async result => {
            if (!result.isConfirmed) return;

            try {
                const res = await fetch(btn.dataset.deleteUrl, {
                    method: "POST",
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                });
                const data = await res.json();

                if (data.status === "success") Swal.fire({ icon: "success", title: "Dihapus" }).then(() => location.reload());
                else Swal.fire({ icon: "error", title: "Gagal", text: data.message });
            } catch (err) {
                Swal.fire({ icon: "error", title: "Error", text: "Gagal menghapus pertanyaan" });
                console.error(err);
            }
        });
    });

    // =====================
    // Edit question
    // =====================
    document.addEventListener("click", async function(e) {
        const btn = e.target.closest(".edit-question");
        if (!btn) return;

        try {
            const res = await fetch(btn.dataset.editUrl, { headers: { "X-Requested-With": "XMLHttpRequest" }});
            const data = await res.json();

            if (data.status !== "success") throw new Error(data.message || "Gagal mengambil data pertanyaan");

            formContainer.classList.remove("hidden");
            form.action = btn.dataset.updateUrl;

            // Tambahkan hidden field question_id jika belum ada
            let hidden = form.querySelector('input[name="question_id"]');
            if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'question_id';
                form.appendChild(hidden);
            }
            hidden.value = data.question.id;

            form.querySelector('[name="question_text"]').value = data.question.question_text || '';
            form.querySelector('[name="question_type"]').value = data.question.question_type || '';
            form.querySelector('[name="order_no"]').value = data.question.order_no || '';
            form.querySelector('[name="is_required"]').checked = data.question.is_required == 1;

            typeSelect.dispatchEvent(new Event("change"));

            // Fill options
            optionList.innerHTML = "";
            if (Array.isArray(data.options) && data.options.length) {
                data.options.forEach(opt => {
                    const div = document.createElement("div");
                    div.classList.add("option-item");
                    div.innerHTML = `
                        <input type="text" name="options[]" class="form-control" value="${opt.text}" placeholder="Option text...">
                        <input type="text" name="option_values[]" class="form-control" value="${opt.value || ''}" placeholder="Value (optional)">
                        <button type="button" class="btn btn-danger btn-icon remove-option"><i class="fas fa-times"></i></button>
                    `;
                    optionList.appendChild(div);
                });
            }

        } catch (err) {
            Swal.fire({ icon: "error", title: "Error", text: err.message || "Gagal mengambil data pertanyaan" });
            console.error(err);
        }
    });

    // =====================
    // Duplicate question
    // =====================
    document.addEventListener("click", function(e) {
        const btn = e.target.closest(".duplicate-question");
        if (!btn) return;

        Swal.fire({
            title: "Duplicate question?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes"
        }).then(async (result) => {
            if (!result.isConfirmed) return;

            try {
                const res = await fetch(btn.dataset.duplicateUrl, {
                    method: "POST",
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                });
                const data = await res.json();

                if (data.status === "success") Swal.fire({ icon: "success", title: "Duplicated!" }).then(() => location.reload());
                else Swal.fire({ icon: "error", title: "Failed", text: data.message });
            } catch (err) {
                Swal.fire({ icon: "error", title: "Error", text: "Gagal menduplikasi pertanyaan" });
                console.error(err);
            }
        });
    });
});
