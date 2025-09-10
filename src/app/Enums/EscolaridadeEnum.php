<?php

namespace App\Enums;

enum EscolaridadeEnum: string
{
    case FUNDAMENTAL_INCOMPLETO = 'fundamental_incompleto';
    case FUNDAMENTAL_COMPLETO = 'fundamental_completo';
    case MEDIO_INCOMPLETO = 'medio_incompleto';
    case MEDIO_COMPLETO = 'medio_completo';
    case SUPERIOR_INCOMPLETO = 'superior_incompleto';
    case SUPERIOR_COMPLETO = 'superior_completo';
    case POS_GRADUACAO = 'pos_graduacao';
    case MESTRADO = 'mestrado';
    case DOUTORADO = 'doutorado';

    /**
     * Retorna a lista de valores aceitos
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Retorna o array de escolaridades para exibição
     */
    public static function options(): array
    {
        return [
            self::FUNDAMENTAL_INCOMPLETO->value => 'Ensino Fundamental Incompleto',
            self::FUNDAMENTAL_COMPLETO->value => 'Ensino Fundamental Completo',
            self::MEDIO_INCOMPLETO->value => 'Ensino Médio Incompleto',
            self::MEDIO_COMPLETO->value => 'Ensino Médio Completo',
            self::SUPERIOR_INCOMPLETO->value => 'Ensino Superior Incompleto',
            self::SUPERIOR_COMPLETO->value => 'Ensino Superior Completo',
            self::POS_GRADUACAO->value => 'Pós-graduação',
            self::MESTRADO->value => 'Mestrado',
            self::DOUTORADO->value => 'Doutorado',
        ];
    }

    /**
     * Retorna a descrição formatada da escolaridade
     */
    public function getLabel(): string
    {
        return self::options()[$this->value] ?? $this->value;
    }

    /**
     * Retorna a regra de validação para Laravel
     */
    public static function validationRule(): string
    {
        return 'in:'.implode(',', self::values());
    }
}
