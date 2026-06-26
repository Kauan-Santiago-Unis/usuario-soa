<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'nivel_acesso_id');
    }
}
