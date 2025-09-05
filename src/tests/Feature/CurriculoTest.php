<?php

namespace Tests\Feature;

use App\Models\Curriculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CurriculoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Mail::fake();
    }

    #[Test]
    public function formulario_de_curriculo_pode_ser_acessado()
    {
        $response = $this->get('/curriculos/enviar');

        $response->assertStatus(200);
    }

    #[Test]
    public function curriculo_pode_ser_enviado_com_dados_validos()
    {
        $arquivo = UploadedFile::fake()->create('curriculo.pdf', 512, 'application/pdf');

        $dados = [
            'nome' => 'João Silva dos Santos',
            'email' => 'joao@exemplo.com',
            'telefone' => '(11) 99999-9999',
            'cargo_desejado' => 'Desenvolvedor Full Stack',
            'escolaridade' => 'superior_completo',
            'observacoes' => 'Tenho experiência em Laravel e Vue.js',
            'arquivo' => $arquivo,
        ];

        $response = $this->post('/curriculos', $dados);

        $response->assertRedirect('/curriculos/sucesso');

        $this->assertDatabaseHas('curriculos', [
            'nome' => 'João Silva dos Santos',
            'email' => 'joao@exemplo.com',
            'telefone' => '(11) 99999-9999',
            'cargo_desejado' => 'Desenvolvedor Full Stack',
            'escolaridade' => 'superior_completo',
            'observacoes' => 'Tenho experiência em Laravel e Vue.js',
        ]);

        Storage::disk('public')->assertExists('curriculos/'.basename(Curriculo::first()->arquivo_path));
    }

    #[Test]
    public function nome_e_obrigatorio()
    {
        $dados = $this->dadosValidos(['nome' => '']);

        $response = $this->post('/curriculos', $dados);

        $response->assertSessionHasErrors('nome');
        $this->assertDatabaseEmpty('curriculos');
    }

    #[Test]
    public function email_e_obrigatorio()
    {
        $dados = $this->dadosValidos(['email' => '']);

        $response = $this->post('/curriculos', $dados);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseEmpty('curriculos');
    }

    #[Test]
    public function telefone_e_obrigatorio()
    {
        $dados = $this->dadosValidos(['telefone' => '']);

        $response = $this->post('/curriculos', $dados);

        $response->assertSessionHasErrors('telefone');
        $this->assertDatabaseEmpty('curriculos');
    }

    #[Test]
    public function cargo_desejado_e_obrigatorio()
    {
        $dados = $this->dadosValidos(['cargo_desejado' => '']);

        $response = $this->post('/curriculos', $dados);

        $response->assertSessionHasErrors('cargo_desejado');
        $this->assertDatabaseEmpty('curriculos');
    }

    #[Test]
    public function escolaridade_e_obrigatoria()
    {
        $dados = $this->dadosValidos(['escolaridade' => '']);

        $response = $this->post('/curriculos', $dados);

        $response->assertSessionHasErrors('escolaridade');
        $this->assertDatabaseEmpty('curriculos');
    }

    #[Test]
    public function arquivo_e_obrigatorio()
    {
        $dados = $this->dadosValidos();
        unset($dados['arquivo']);

        $response = $this->post('/curriculos', $dados);

        $response->assertSessionHasErrors('arquivo');
        $this->assertDatabaseEmpty('curriculos');
    }

    #[Test]
    public function observacoes_sao_opcionais()
    {
        $dados = $this->dadosValidos(['observacoes' => '']);

        $response = $this->post('/curriculos', $dados);

        $response->assertRedirect('/curriculos/sucesso');
        $this->assertDatabaseHas('curriculos', [
            'observacoes' => null,
        ]);
    }

    #[Test]
    public function arquivo_deve_ter_extensao_valida()
    {
        $arquivo = UploadedFile::fake()->create('curriculo.txt', 512, 'text/plain');
        $dados = $this->dadosValidos(['arquivo' => $arquivo]);

        $response = $this->post('/curriculos', $dados);

        $response->assertSessionHasErrors('arquivo');
        $this->assertDatabaseEmpty('curriculos');
    }

    #[Test]
    public function arquivo_pdf_e_aceito()
    {
        $arquivo = UploadedFile::fake()->create('curriculo.pdf', 512, 'application/pdf');
        $dados = $this->dadosValidos(['arquivo' => $arquivo]);

        $response = $this->post('/curriculos', $dados);

        $response->assertRedirect('/curriculos/sucesso');
        $this->assertDatabaseCount('curriculos', 1);
    }

    #[Test]
    public function arquivo_doc_e_aceito()
    {
        $arquivo = UploadedFile::fake()->create('curriculo.doc', 512, 'application/msword');
        $dados = $this->dadosValidos(['arquivo' => $arquivo]);

        $response = $this->post('/curriculos', $dados);

        $response->assertRedirect('/curriculos/sucesso');
        $this->assertDatabaseCount('curriculos', 1);
    }

    #[Test]
    public function arquivo_docx_e_aceito()
    {
        $arquivo = UploadedFile::fake()->create('curriculo.docx', 512, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $dados = $this->dadosValidos(['arquivo' => $arquivo]);

        $response = $this->post('/curriculos', $dados);

        $response->assertRedirect('/curriculos/sucesso');
        $this->assertDatabaseCount('curriculos', 1);
    }

    #[Test]
    public function arquivo_nao_pode_ser_maior_que_1mb()
    {
        $arquivo = UploadedFile::fake()->create('curriculo.pdf', 2048, 'application/pdf'); // 2MB
        $dados = $this->dadosValidos(['arquivo' => $arquivo]);

        $response = $this->post('/curriculos', $dados);

        $response->assertSessionHasErrors('arquivo');
        $this->assertDatabaseEmpty('curriculos');
    }

    #[Test]
    public function email_duplicado_nao_e_aceito()
    {
        // Criar primeiro currículo
        $this->post('/curriculos', $this->dadosValidos(['email' => 'teste@exemplo.com']));

        // Tentar criar segundo currículo com o mesmo email
        $response = $this->post('/curriculos', $this->dadosValidos(['email' => 'teste@exemplo.com']));

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('curriculos', 1);
    }

    #[Test]
    public function escolaridade_deve_ter_valor_valido()
    {
        $dados = $this->dadosValidos(['escolaridade' => 'escolaridade_inexistente']);

        $response = $this->post('/curriculos', $dados);

        $response->assertSessionHasErrors('escolaridade');
        $this->assertDatabaseEmpty('curriculos');
    }

    #[Test]
    public function ip_e_registrado_automaticamente()
    {
        $dados = $this->dadosValidos();

        $this->post('/curriculos', $dados);

        $curriculo = Curriculo::first();
        $this->assertNotNull($curriculo->ip_address);
    }

    #[Test]
    public function data_de_envio_e_registrada_automaticamente()
    {
        $dados = $this->dadosValidos();

        $this->post('/curriculos', $dados);

        $curriculo = Curriculo::first();
        $this->assertNotNull($curriculo->submitted_at);
    }

    #[Test]
    public function email_e_enviado_apos_submissao()
    {
        $dados = $this->dadosValidos();

        $this->post('/curriculos', $dados);

        Mail::assertSent(\App\Mail\CurriculoRecebido::class);
    }

    /**
     * Retorna dados válidos para o teste
     */
    protected function dadosValidos(array $sobrescrever = []): array
    {
        $arquivo = UploadedFile::fake()->create('curriculo.pdf', 512, 'application/pdf');

        return array_merge([
            'nome' => 'João Silva dos Santos',
            'email' => 'teste'.uniqid().'@exemplo.com',
            'telefone' => '(11) 99999-9999',
            'cargo_desejado' => 'Desenvolvedor Full Stack',
            'escolaridade' => 'superior_completo',
            'observacoes' => 'Experiência em desenvolvimento web',
            'arquivo' => $arquivo,
        ], $sobrescrever);
    }
}
