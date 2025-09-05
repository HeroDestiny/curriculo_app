<?php

namespace Tests\Unit;

use App\Models\Curriculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CurriculoModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function model_tem_fillable_corretos()
    {
        $fillable = [
            'nome',
            'email',
            'telefone',
            'cargo_desejado',
            'escolaridade',
            'observacoes',
            'arquivo_path',
            'arquivo_original_name',
            'ip_address',
            'submitted_at',
        ];

        $curriculo = new Curriculo();

        $this->assertEquals($fillable, $curriculo->getFillable());
    }

    #[Test]
    public function casts_estao_definidos_corretamente()
    {
        $curriculo = new Curriculo();
        $casts = $curriculo->getCasts();

        $this->assertArrayHasKey('created_at', $casts);
        $this->assertArrayHasKey('updated_at', $casts);
        $this->assertArrayHasKey('submitted_at', $casts);
        $this->assertEquals('datetime', $casts['submitted_at']);
    }

    #[Test]
    public function escolaridade_formatada_retorna_valor_correto()
    {
        $curriculo = Curriculo::factory()->make([
            'escolaridade' => 'superior_completo'
        ]);

        $this->assertEquals('Ensino Superior Completo', $curriculo->escolaridade_formatada);
    }

    #[Test]
    public function escolaridade_formatada_retorna_valor_original_se_nao_encontrada()
    {
        $curriculo = Curriculo::factory()->make([
            'escolaridade' => 'escolaridade_inexistente'
        ]);

        $this->assertEquals('escolaridade_inexistente', $curriculo->escolaridade_formatada);
    }

    #[Test]
    public function todas_escolaridades_tem_formatacao()
    {
        $escolaridades = [
            'fundamental_incompleto' => 'Ensino Fundamental Incompleto',
            'fundamental_completo' => 'Ensino Fundamental Completo',
            'medio_incompleto' => 'Ensino Médio Incompleto',
            'medio_completo' => 'Ensino Médio Completo',
            'superior_incompleto' => 'Ensino Superior Incompleto',
            'superior_completo' => 'Ensino Superior Completo',
            'pos_graduacao' => 'Pós-graduação',
            'mestrado' => 'Mestrado',
            'doutorado' => 'Doutorado',
        ];

        foreach ($escolaridades as $valor => $esperado) {
            $curriculo = Curriculo::factory()->make([
                'escolaridade' => $valor
            ]);

            $this->assertEquals($esperado, $curriculo->escolaridade_formatada, 
                "Escolaridade {$valor} deveria retornar {$esperado}"
            );
        }
    }

    #[Test]
    public function curriculo_pode_ser_criado()
    {
        $dados = [
            'nome' => 'João Silva',
            'email' => 'joao@exemplo.com',
            'telefone' => '(11) 99999-9999',
            'cargo_desejado' => 'Desenvolvedor',
            'escolaridade' => 'superior_completo',
            'observacoes' => 'Observações de teste',
            'arquivo_path' => 'curriculos/teste.pdf',
            'arquivo_original_name' => 'curriculo_joao.pdf',
            'ip_address' => '127.0.0.1',
            'submitted_at' => now(),
        ];

        $curriculo = Curriculo::create($dados);

        $this->assertDatabaseHas('curriculos', [
            'nome' => 'João Silva',
            'email' => 'joao@exemplo.com',
            'telefone' => '(11) 99999-9999',
            'cargo_desejado' => 'Desenvolvedor',
            'escolaridade' => 'superior_completo',
            'observacoes' => 'Observações de teste',
            'arquivo_path' => 'curriculos/teste.pdf',
            'arquivo_original_name' => 'curriculo_joao.pdf',
            'ip_address' => '127.0.0.1',
        ]);

        $this->assertInstanceOf(Curriculo::class, $curriculo);
        $this->assertNotNull($curriculo->id);
    }
}
