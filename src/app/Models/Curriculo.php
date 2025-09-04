<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curriculo extends Model
{
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cargo_desejado',
        'escolaridade',
        'observacoes',
        'arquivo_path',
        'arquivo_original_name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getEscolaridadeFormatadaAttribute()
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
            'doutorado' => 'Doutorado'
        ];

        return $escolaridades[$this->escolaridade] ?? $this->escolaridade;
    }
}
