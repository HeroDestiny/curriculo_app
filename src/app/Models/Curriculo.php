<?php

namespace App\Models;

use App\Enums\EscolaridadeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculo extends Model
{
    use HasFactory;

    protected $fillable = [
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

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'submitted_at' => 'datetime',
        'escolaridade' => EscolaridadeEnum::class,
    ];

    public function getEscolaridadeFormatadaAttribute()
    {
        if ($this->escolaridade instanceof EscolaridadeEnum) {
            return $this->escolaridade->getLabel();
        }

        // Fallback para compatibilidade com dados antigos
        return EscolaridadeEnum::options()[$this->escolaridade] ?? $this->escolaridade;
    }
}
