<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'telefone',
        'email_verificado',
        'data_verificacao',
        'data_nascimento',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

//    public function nivelAcesso()
//    {
//        return this->hasOne(NivelAcesso::class, 'nivel_acesso', 'nivel_acesso_id', )
//    }
}
