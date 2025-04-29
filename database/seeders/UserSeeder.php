<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataArray = [
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => '2024-06-05',
                'remember_token' => '',
                'rol_id' => 1
            ],
            [
                'name' => 'Felipe',
                'email' => 'felipe@admin.com',
                'email_verified_at' => '2024-06-05',
                'remember_token' => '',
                'rol_id' => 1
            ],
        ];

        foreach ($dataArray as $value) {
            $data = User::where('email', $value['email'])->first() ?? new User();
            $data->name = $value['name'];
            $data->email = $value['email'];
            $data->email_verified_at = $value['email_verified_at'];
            $data->remember_token = $value['remember_token'];
            $data->password = Hash::make(123456789);
            $data->save();
        }
    }
}
