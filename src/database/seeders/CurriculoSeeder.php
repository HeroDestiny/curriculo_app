<?php

namespace Database\Seeders;

use App\Models\Curriculo;
use Illuminate\Database\Seeder;

class CurriculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar 20 currículos com dados aleatórios
        Curriculo::factory()->count(20)->create();

        // Criar 5 currículos de desenvolvedores
        Curriculo::factory()->desenvolvedor()->count(5)->create();

        // Criar 3 currículos de candidatos júniores
        Curriculo::factory()->junior()->count(3)->create();

        // Criar 2 currículos de candidatos sêniores
        Curriculo::factory()->senior()->count(2)->create();

        // Criar currículos com escolaridade específica
        Curriculo::factory()->superiorCompleto()->count(3)->create();
        Curriculo::factory()->posGraduacao()->count(2)->create();

        // Exemplo de currículo com dados específicos
        Curriculo::factory()->create([
            'nome' => 'João da Silva',
            'email' => 'joao.silva@exemplo.com',
            'telefone' => '(11) 99999-9999',
            'cargo_desejado' => 'Desenvolvedor Full Stack',
            'escolaridade' => 'superior_completo',
            'observacoes' => 'Desenvolvedor experiente com foco em Laravel e Vue.js',
            'ip_address' => '127.0.0.1',
        ]);

        $this->command->info('Currículos criados com sucesso!');
    }
}
