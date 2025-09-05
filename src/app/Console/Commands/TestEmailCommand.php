<?php

namespace App\Console\Commands;

use App\Mail\CurriculoRecebido;
use App\Models\Curriculo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    protected $signature = 'email:test {--email=admin@exemplo.com : E-mail de destino}';

    protected $description = 'Testa o envio de e-mail do sistema de currículos';

    public function handle()
    {
        $email = $this->option('email');

        $this->info('Iniciando teste de e-mail...');
        $this->info("E-mail de destino: {$email}");

        try {
            // Criar um currículo de teste no banco de dados
            $curriculo = Curriculo::factory()->create([
                'nome' => 'João Silva Teste',
                'email' => 'joao.teste@exemplo.com',
                'telefone' => '(11) 99999-9999',
                'cargo_desejado' => 'Desenvolvedor PHP',
                'escolaridade' => 'superior_completo',
                'observacoes' => 'Este é um currículo de teste para verificar o envio de e-mails.',
                'arquivo_original_name' => 'curriculo_joao_teste.pdf',
                'ip_address' => '127.0.0.1',
                'submitted_at' => now(),
            ]);

            $this->info("✓ Currículo de teste criado (ID: {$curriculo->id})");

            // Enviar e-mail
            Mail::to($email)->send(new CurriculoRecebido($curriculo));

            $this->info('✓ E-mail enviado com sucesso!');

            if (config('mail.default') === 'log') {
                $this->warn('NOTA: O sistema está configurado para MAIL_MAILER=log');
                $this->warn('O e-mail foi enviado para fila. Execute o comando abaixo para processá-lo:');
                $this->line('php artisan queue:work --once');
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('✗ Erro ao enviar e-mail: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
