    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <div>
            <div>
                <form action="<?= base_url('/admin/pengguna/tambahPengguna/post') ?>" method="post">
                    <div>
                       <div>
                        <label for="username">username</label>
                        <input type="text" name="username" id="username" required>
                    </div>
                    <div>
                        <label for="email">email</label>
                        <input type="text" name="email" id="email" required>
                    </div>
                    <div>
                        <label for="status">status</label>
                        <select name="status" id="status">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak-Aktif">Tidak-Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label for="Group">Group</label>
                      <select name="Group" id="Group">
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= esc($role['id']) ?>"><?= esc($role['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="password">password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    </div>
                    <div>
                        <div>
                            <label for="nama_lengkap">Nama lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" required>
                        </div>
                    </div> 
                    <div>
                        <button type="submit"> simpan </button>
                    </div>            
                </form> 
            </div>
        </div>
    </body>
    </html>