<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $model = model('UserModel');
        $model->insert([
            'username' => 'admin',
            'useremail' => 'akmalabdilah6@gmail.com',
            'userpassword' => password_hash('akmal123321', PASSWORD_DEFAULT),
        ]);
    }
}
