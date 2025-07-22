<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Verifica se o usuário admin já existe
        $adminExists = User::where('email', 'admin@diariobordo.com')->exists();
        
        if (!$adminExists) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@diariobordo.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'cpf' => '11111111111',
                'telefone' => '(42) 99999-9999',
                'cargo' => 'Administrador do Sistema',
                'precisa_trocar_senha' => false,
                'email_verified_at' => now(),
            ]);
        }
    }
}
