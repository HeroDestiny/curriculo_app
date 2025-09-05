<?php

namespace Tests\Unit;

use App\Rules\TelefoneBrasileiroRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TelefoneBrasileiroRuleTest extends TestCase
{
    protected TelefoneBrasileiroRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new TelefoneBrasileiroRule();
    }

    #[Test]
    public function telefone_celular_formato_correto_e_valido()
    {
        $telefones = [
            '(11) 99999-9999',
            '(21) 98888-8888',
            '(85) 97777-7777',
        ];

        foreach ($telefones as $telefone) {
            $erro = null;
            $this->rule->validate('telefone', $telefone, function ($mensagem) use (&$erro) {
                $erro = $mensagem;
            });

            $this->assertNull($erro, "Telefone {$telefone} deveria ser válido");
        }
    }

    #[Test]
    public function telefone_fixo_formato_correto_e_valido()
    {
        $telefones = [
            '(11) 3333-3333',
            '(21) 2222-2222',
            '(85) 3456-7890',
        ];

        foreach ($telefones as $telefone) {
            $erro = null;
            $this->rule->validate('telefone', $telefone, function ($mensagem) use (&$erro) {
                $erro = $mensagem;
            });

            $this->assertNull($erro, "Telefone {$telefone} deveria ser válido");
        }
    }

    #[Test]
    public function telefone_apenas_numeros_e_valido()
    {
        $telefones = [
            '11999999999', // Celular
            '1133333333',  // Fixo
        ];

        foreach ($telefones as $telefone) {
            $erro = null;
            $this->rule->validate('telefone', $telefone, function ($mensagem) use (&$erro) {
                $erro = $mensagem;
            });

            $this->assertNull($erro, "Telefone {$telefone} deveria ser válido");
        }
    }

    #[Test]
    public function telefone_com_menos_de_10_digitos_e_invalido()
    {
        $erro = null;
        $this->rule->validate('telefone', '119999999', function ($mensagem) use (&$erro) {
            $erro = $mensagem;
        });

        $this->assertNotNull($erro);
        $this->assertEquals('O telefone deve ter 10 ou 11 dígitos.', $erro);
    }

    #[Test]
    public function telefone_com_mais_de_11_digitos_e_invalido()
    {
        $erro = null;
        $this->rule->validate('telefone', '119999999999', function ($mensagem) use (&$erro) {
            $erro = $mensagem;
        });

        $this->assertNotNull($erro);
        $this->assertEquals('O telefone deve ter 10 ou 11 dígitos.', $erro);
    }

    #[Test]
    public function celular_sem_nono_digito_e_invalido()
    {
        $erro = null;
        $this->rule->validate('telefone', '11888889999', function ($mensagem) use (&$erro) {
            $erro = $mensagem;
        });

        $this->assertNotNull($erro);
        $this->assertEquals('Para celular (11 dígitos), o terceiro dígito deve ser 9.', $erro);
    }

    #[Test]
    public function ddd_invalido_e_rejeitado()
    {
        $erro = null;
        $this->rule->validate('telefone', '(09) 99999-9999', function ($mensagem) use (&$erro) {
            $erro = $mensagem;
        });

        $this->assertNotNull($erro);
        $this->assertEquals('Código de área (DDD) inválido.', $erro);
    }

    #[Test]
    public function telefone_com_todos_digitos_iguais_e_invalido()
    {
        $erro = null;
        $this->rule->validate('telefone', '1111111111', function ($mensagem) use (&$erro) {
            $erro = $mensagem;
        });

        $this->assertNotNull($erro);
        $this->assertEquals('O telefone não pode conter todos os dígitos iguais.', $erro);
    }

    #[Test]
    public function formato_celular_incorreto_e_invalido()
    {
        $erro = null;
        $this->rule->validate('telefone', '(11) 9999-99999', function ($mensagem) use (&$erro) {
            $erro = $mensagem;
        });

        $this->assertNotNull($erro);
        $this->assertEquals('Celular (11 dígitos) deve estar no formato (XX) XXXXX-XXXX.', $erro);
    }

    #[Test]
    public function formato_fixo_incorreto_e_invalido()
    {
        $erro = null;
        $this->rule->validate('telefone', '(11) 33333-333', function ($mensagem) use (&$erro) {
            $erro = $mensagem;
        });

        $this->assertNotNull($erro);
        $this->assertEquals('Telefone fixo (10 dígitos) deve estar no formato (XX) XXXX-XXXX.', $erro);
    }

    #[Test]
    public function ddds_validos_sao_aceitos()
    {
        $dddsValidos = [
            11, 21, 31, 41, 51, 61, 71, 81, 91, // Algumas capitais
            85, 86, 87, 88, 89, // Nordeste
        ];

        foreach ($dddsValidos as $ddd) {
            $telefone = sprintf('(%02d) 99999-9999', $ddd);
            $erro = null;
            
            $this->rule->validate('telefone', $telefone, function ($mensagem) use (&$erro) {
                $erro = $mensagem;
            });

            $this->assertNull($erro, "DDD {$ddd} deveria ser válido");
        }
    }
}

