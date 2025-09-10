<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário administrador
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('123456'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Criar usuário recrutador normal (sem acesso aos currículos)
        User::firstOrCreate(
            ['email' => 'recrutador@exemplo.com'],
            [
                'name' => 'Recrutador Teste',
                'password' => Hash::make('123456'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Usuários criados com sucesso!');
        $this->command->info('Admin: admin@admin.com / 123456');
        $this->command->info('Recrutador: recrutador@exemplo.com / 123456');
    }
}
