<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table = 'account';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'id_role'];

    public function getByUsernameOrEmail($input)
    {
        return $this->where('username', $input)
            ->orWhere('email', $input)
            ->first();
    }
}
