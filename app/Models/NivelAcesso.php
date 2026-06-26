<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelAcesso extends Model
{
    protected $table = 'niveis_acesso';

    protected $fillable = [
        'nome',
        'permissoes',
    ];

    protected $casts = [
        'permissoes' => 'array',
    ];
}