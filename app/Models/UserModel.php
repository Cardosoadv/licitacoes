<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['open_id', 'name', 'email', 'avatar_url', 'last_login'];
    protected $useTimestamps = true;
    protected $validationRules = [
        'name' => 'required|min_length[3]',
        'email' => 'required|valid_email',
    ];

    public function findByOpenId($openId)
    {
        return $this->where('open_id', $openId)->first();
    }

    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }
}