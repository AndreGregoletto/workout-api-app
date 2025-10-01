<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            "name"        => "Andre Gregoletto",
            "social_name" => "O Lobo",
            "email"       => "andre@email.com",
            "password"    => "12345678",
            "cpf"         => "12345678901",
            "phone"       => "5511977195214",
            "date_birth"  => "1997-12-20",
            "admin"       => 1,
            "status"      => 1
        ]);
    }
}
