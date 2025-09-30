<?php
namespace App\Models\User;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table      = 'account';
    protected $primaryKey = 'id';
  protected $allowedFields = [
    'username',
    'email',
    'password',
    'status',
    'id_role',
    'id_surveyor',
    'remember_token',
    'reset_token',
    'reset_expires',
    'created_at',
    'updated_at',
    'foto'   // ✅ tambah ini
];


    protected $useTimestamps = true;

    /**
     * Simpan token "remember me"
     */
    public function saveRememberToken($userId, $token)
    {
        return $this->update($userId, ['remember_token' => $token]);
    }

    /**
     * Cari user berdasarkan remember_token
     */
    public function getUserByToken($token)
    {
        return $this->where('remember_token', $token)->first();
    }

    /**
     * Simpan token reset password + expired
     */
    public function setResetToken($userId, $token, $expires)
    {
        return $this->update($userId, [
            'reset_token'   => $token,
            'reset_expires' => $expires
        ]);
    }

    /**
     * Cari user berdasarkan reset_token yang belum expired
     */
    public function getUserByResetToken($token)
    {
        return $this->where('reset_token', $token)
            ->where('reset_expires >=', date('Y-m-d H:i:s'))
            ->first();
    }

    /**
     * Update password + hapus reset_token & expired
     */
    public function updatePasswordAndExpire($userId, $newPassword)
    {
        return $this->update($userId, [
            'password'      => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_token'   => null,
            'reset_expires' => null
        ]);
    }
}
