<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@techshop.tsd'], 
            [
                'ime' => 'Admin',
                'prezime' => 'User',
                'password' => Hash::make('TlwK[(9}fQ1~g;Q7]xo-H~(J!Vz}.4PouVhrnH^ir,VK-aGqAG'), 
                'telefon' => '000000000',
                'is_admin' => true,
            ]
        );
    }
}
