<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'telefone',
        'senha',
        'email_verificado',
        'data_verificacao',
        'data_nascimento',
        'nivel_acesso_id',
        'remember_token',
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    protected $casts = [
        'email_verificado' => 'boolean',
        'data_verificacao' => 'datetime',
        'data_nascimento' => 'date',
    ];

    public function getAuthPassword(): string
    {
        return $this->senha;
    }

    public function nivelAcesso()
    {
        return $this->belongsTo(NivelAcesso::class, 'nivel_acesso_id');
    }

    public function enderecos()
    {
        return $this->hasMany(Endereco::class, 'usuario_id');
    }

    public function isFuncionario(): bool
    {
        return optional($this->nivelAcesso)->nome === 'funcionario';
    }

    public function isCliente(): bool
    {
        return optional($this->nivelAcesso)->nome === 'cliente';
    }
}
