<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Endereco extends Model
{

    protected $fillable = [
        'usuario_id',
        'logradouro',
        'numero',
        'cep',
        'cidade',
        'estado',
        'complemento',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}