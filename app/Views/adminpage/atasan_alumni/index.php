<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="max-w-5xl mx-auto">
  <div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Relasi Atasan ↔ Alumni</h2>

    <!-- Form Simpan Relasi -->
    <form id="form-relasi" method="POST" action="<?= site_url('admin/relasi-atasan-alumni/store') ?>" class="space-y-4">
      <?= csrf_field() ?>

      <div>
        <label class="block text-sm font-medium text-gray-700">Pilih Atasan</label>
        <select name="id_atasan" id="id_atasan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
          <option value="">-- Pilih Atasan --</option>
          <?php foreach ($atasan as $a): ?>
            <option value="<?= esc($a['id']) ?>"><?= esc($a['nama_lengkap']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
          <label class="block text-sm font-medium text-gray-700">Tahun Lulus</label>
          <select name="filter_angkatan" id="filter_angkatan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Semua Angkatan</option>
            <?php $currentYear = date('Y');
            for ($year = $currentYear; $year >= $currentYear - 50; $year--): ?>
              <option value="<?= $year ?>"><?= $year ?></option>
            <?php endfor; ?>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Prodi</label>
          <select id="filter_prodi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Semua Prodi</option>
            <?php if (!empty($prodiList)): foreach ($prodiList as $p): ?>
              <option value="<?= esc($p['id']) ?>"><?= esc($p['nama_prodi']) ?></option>
            <?php endforeach; endif; ?>
          </select>
        </div>

        <div class="md:col-span-1 md:col-start-3">
          <label class="block text-sm font-medium text-gray-700">Cari (nama/NIM)</label>
          <input type="text" id="filter_q" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ketik nama atau NIM...">
        </div>

        <div class="flex items-end">
          <button type="button" id="btn-cari" class="w-full inline-flex justify-center py-2 px-3 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
            Cari Alumni
          </button>
        </div>
      </div>

      <div class="mt-2">
        <label class="block text-sm font-medium text-gray-700">Tambah Alumni</label>
        <div class="flex gap-2 mt-1">
          <select id="alumni_dropdown" class="flex-1 rounded-md border-gray-300 shadow-sm">
            <option value="">(Pilih alumni setelah pencarian)</option>
          </select>
          <button type="button" id="btn-tambah-alumni" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md bg-green-600 text-white hover:bg-green-700">
            Tambah
          </button>
        </div>
        <p class="text-xs text-gray-500 mt-1">Pilih alumni lalu klik Tambah. Alumni yang ditambahkan akan muncul di bawah.</p>
      </div>

      <div class="mt-4">
        <h6 class="font-medium text-gray-700">Alumni Terpilih:</h6>
        <div id="selected-alumni-list" class="mt-2 flex flex-wrap gap-2">
          <!-- badges muncul di sini -->
        </div>
      </div>

      <div class="pt-3">
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700">
          Simpan Relasi
        </button>
      </div>
    </form>
  </div>

  <!-- -------table atasan alumni------- -->
  <div class="bg-white p-6 rounded-xl shadow-sm mt-6">
    <h3 class="text-lg font-semibold mb-4">Daftar Relasi Atasan ↔ Alumni</h3>
    <table class="min-w-full border border-gray-200">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-3 text-left border-r">Atasan</th>
          <th class="p-3 text-left border-r">Alumni</th>
          <th class="p-3 text-center w-24">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($grouped)): ?>
          <tr>
            <td colspan="3" class="p-4 text-center text-gray-500">
              Belum ada relasi. Silakan tambahkan relasi di form atas.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($grouped as $id_atasan => $data): ?>
            <?php $alumniCount = count($data['alumni']); ?>
            <?php foreach ($data['alumni'] as $index => $alumni): ?>
              <tr class="border-b hover:bg-gray-50">
                <?php if ($index === 0): ?>
                  <td rowspan="<?= $alumniCount ?>" class="p-3 font-semibold text-gray-800 bg-gray-50 border-r align-top">
                    <?= esc($data['nama_atasan']) ?>
                  </td>
                <?php endif; ?>

                <td class="p-3 border-r">
                  <div class="flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                      <?= esc($alumni['nama_alumni']) ?>
                    </span>
                    <?php if (isset($alumni['nim_alumni'])): ?>
                      <span class="text-xs text-gray-500">(NIM: <?= esc($alumni['nim_alumni']) ?>)</span>
                    <?php endif; ?>
                  </div>
                </td>

                <td class="p-3 text-center">
                  <a href="<?= base_url('admin/relasi-atasan-alumni/delete/' . $alumni['id_relasi'])?>"
                     class="btn-delete inline-flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full text-sm transition-colors shadow-sm"
                     data-nama="<?= esc($alumni['nama_alumni']) ?>"
                     data-atasan="<?= esc($data['nama_atasan']) ?>"
                     title="Hapus relasi ini">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JS Function -->
<script>
(function(){
  // --- Helper: ambil CSRF dari form (CI4) ---
  function getCsrfToken() {
    const all = document.querySelectorAll('#form-relasi input[type="hidden"]');
    for (const i of all) {
      if (i.name && i.name.toLowerCase().includes('csrf')) return {name: i.name, value: i.value};
    }
    return null;
  }

  const btnCari = document.getElementById('btn-cari');
  const alumniDropdown = document.getElementById('alumni_dropdown');
  const selectedList = document.getElementById('selected-alumni-list');
  const btnTambah = document.getElementById('btn-tambah-alumni');
  const formRelasi = document.getElementById('form-relasi');

  // --- Fetch alumni berdasarkan filter ---
  async function fetchAlumni() {
    const angkatan = document.getElementById('filter_angkatan').value;
    const id_prodi  = document.getElementById('filter_prodi').value;
    const q = document.getElementById('filter_q').value.trim();

    const csrf = getCsrfToken();
    const body = new URLSearchParams();
    if (angkatan) body.append('angkatan', angkatan);
    if (id_prodi) body.append('id_prodi', id_prodi);
    if (q) body.append('q', q);
    if (csrf) body.append(csrf.name, csrf.value);

    try {
      const res = await fetch("<?= site_url('admin/relasi-atasan-alumni/fetch-alumni') ?>", {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: body.toString(),
        credentials: 'same-origin'
      });

      if (!res.ok) throw new Error('HTTP error ' + res.status);
      const data = await res.json();

      alumniDropdown.innerHTML = '<option value="">-- Pilih Alumni --</option>';

      if (Array.isArray(data) && data.length) {
        data.forEach(item => {
          const o = document.createElement('option');
          o.value = item.id;
          o.textContent = item.text;
          alumniDropdown.appendChild(o);
        });

        Swal.fire({
          icon: 'success',
          title: 'Data ditemukan!',
          text: data.length + ' alumni berhasil dimuat.',
          timer: 1500,
          showConfirmButton: false
        });

      } else {
        alumniDropdown.innerHTML = '<option value="">Tidak ada alumni ditemukan</option>';
        Swal.fire({
          icon: 'info',
          title: 'Tidak ditemukan',
          text: 'Tidak ada alumni dengan filter yang dimasukkan.'
        });
      }

    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: 'Terjadi kesalahan saat mengambil data alumni.'
      });
    }
  }

  btnCari.addEventListener('click', function(e){
    e.preventDefault();
    fetchAlumni();
  });

  // --- Tambah alumni ke daftar ---
  btnTambah.addEventListener('click', function(e){
    e.preventDefault();
    const sel = alumniDropdown.value;
    const text = alumniDropdown.options[alumniDropdown.selectedIndex]?.text || '';
    if (!sel) {
      return Swal.fire({icon: 'warning', title: 'Pilih alumni dahulu!'});
    }

    if (document.querySelector('#selected-alumni-list [data-id="'+sel+'"]')) {
      return Swal.fire({icon: 'info', title: 'Alumni sudah dipilih!'});
    }

    const span = document.createElement('span');
    span.setAttribute('data-id', sel);
    span.className = 'inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-200 text-gray-800 text-sm';
    const txt = document.createElement('span');
    txt.textContent = text;
    span.appendChild(txt);

    const btnRm = document.createElement('button');
    btnRm.type = 'button';
    btnRm.className = 'ml-2 inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-500 text-white text-xs';
    btnRm.innerHTML = '✕';
    btnRm.addEventListener('click', function(){
      const hid = document.getElementById('input-alumni-'+sel);
      if (hid) hid.remove();
      span.remove();
    });

    span.appendChild(btnRm);
    selectedList.appendChild(span);

    const hidden = document.createElement('input');
    hidden.type = 'hidden';
    hidden.name = 'id_alumni[]';
    hidden.value = sel;
    hidden.id = 'input-alumni-'+sel;
    formRelasi.appendChild(hidden);

    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: 'Alumni berhasil ditambahkan ke daftar.',
      timer: 1500,
      showConfirmButton: false
    });
  });

  // --- Konfirmasi hapus relasi dengan SweetAlert2 ---
  document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e){
      e.preventDefault();
      const url = this.href;
      const nama = this.dataset.nama;
      const atasan = this.dataset.atasan;

      Swal.fire({
        title: 'Yakin hapus relasi?',
        text: `${nama} ↔ ${atasan}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });
})();


<?php if (session()->getFlashdata('success')): ?>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: '<?= esc(session()->getFlashdata('success')) ?>',
  timer: 2000,
  showConfirmButton: false
});
<?php elseif (session()->getFlashdata('error')): ?>
Swal.fire({
  icon: 'error',
  title: 'Gagal!',
  text: '<?= esc(session()->getFlashdata('error')) ?>'
});
<?php endif; ?>
</script>

<?= $this->endSection() ?>
