<?= $this->extend('layout/sidebar') ?>
<?= $this->section('content') ?>

<div class="bg-white rounded-xl shadow-md p-8 w-full max-w-3xl mx-auto">
    <h2 class="text-xl font-bold mb-6">Edit Profil Admin</h2>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <form action="<?= base_url('admin/profil/update/' . $admin['id']) ?>" method="post">
        <?= csrf_field() ?>
        
        <!-- Nama Lengkap -->
        <div class="mb-4">
            <label class="block font-medium">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" 
                   value="<?= old('nama_lengkap', $admin['nama_lengkap'] ?? '') ?>"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-300">
        </div>

        <!-- Username -->
        <div class="mb-4">
            <label class="block font-medium">Username</label>
            <input type="text" name="username" 
                   value="<?= old('username', $admin['username'] ?? '') ?>"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-300">
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label class="block font-medium">Email</label>
            <input type="email" name="email" 
                   value="<?= old('email', $admin['email'] ?? '') ?>"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-300">
        </div>

        <!-- No HP -->
        <div class="mb-4">
            <label class="block font-medium">Nomor Telepon / WhatsApp</label>
            <input type="text" name="no_hp" 
                   value="<?= old('no_hp', $admin['no_hp'] ?? '') ?>"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-300">
        </div>

        <!-- Tombol -->
        <div class="flex justify-end gap-2">
            <a href="<?= base_url('admin/profil') ?>" 
               class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Batal</a>
            <button type="submit" 
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Simpan</button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>