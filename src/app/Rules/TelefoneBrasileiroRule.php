<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TelefoneBrasileiroRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove todos os caracteres que não são dígitos
        $cleaned = preg_replace('/\D/', '', $value);

        // Verifica se tem 10 ou 11 dígitos
        if (strlen($cleaned) < 10 || strlen($cleaned) > 11) {
            $fail('O telefone deve ter 10 ou 11 dígitos.');

            return;
        }

        // Para telefone com 11 dígitos, o terceiro dígito deve ser 9 (celular)
        if (strlen($cleaned) === 11 && $cleaned[2] !== '9') {
            $fail('Para celular (11 dígitos), o terceiro dígito deve ser 9.');

            return;
        }

        // Verifica se o código de área é válido (entre 11 e 99, exceto alguns códigos inexistentes)
        $ddd = (int) substr($cleaned, 0, 2);
        $dddValidos = [
            11, 12, 13, 14, 15, 16, 17, 18, 19, // SP
            21, 22, 24, // RJ/ES
            27, 28, // ES
            31, 32, 33, 34, 35, 37, 38, // MG
            41, 42, 43, 44, 45, 46, // PR
            47, 48, 49, // SC
            51, 53, 54, 55, // RS
            61, // DF/GO
            62, 64, // GO
            63, // TO
            65, 66, // MT
            67, // MS
            68, // AC
            69, // RO
            71, 73, 74, 75, 77, // BA
            79, // SE
            81, 87, // PE
            82, // AL
            83, // PB
            84, // RN
            85, 88, // CE
            86, 89, // PI
            91, 93, 94, // PA
            92, 97, // AM
            95, // RR
            96, // AP
            98, 99, // MA
        ];

        if (! in_array($ddd, $dddValidos)) {
            $fail('Código de área (DDD) inválido.');

            return;
        }

        // Verifica se não são todos os dígitos iguais
        if (preg_match('/^(\d)\1+$/', $cleaned)) {
            $fail('O telefone não pode conter todos os dígitos iguais.');

            return;
        }

        // Verifica se o formato original está correto
        $apenasDigitos = preg_match('/^\d{10,11}$/', $value); // verifica se o input original é só números

        if ($apenasDigitos) {
            // Se o input é apenas números, está ok
            return;
        }

        // Se tem máscara, deve verificar se o formato está correto para o número de dígitos
        if (strlen($cleaned) === 11) {
            // Celular deve ter formato (XX) XXXXX-XXXX
            $formatoCelular = preg_match('/^\(\d{2}\)\s*\d{5}-\d{4}$/', trim($value));
            if (! $formatoCelular) {
                $fail('Celular (11 dígitos) deve estar no formato (XX) XXXXX-XXXX.');

                return;
            }
        } elseif (strlen($cleaned) === 10) {
            // Telefone fixo deve ter formato (XX) XXXX-XXXX
            $formatoFixo = preg_match('/^\(\d{2}\)\s*\d{4}-\d{4}$/', trim($value));
            if (! $formatoFixo) {
                $fail('Telefone fixo (10 dígitos) deve estar no formato (XX) XXXX-XXXX.');

                return;
            }
        } else {
            $fail('O telefone deve ter 10 ou 11 dígitos.');
        }
    }
}
