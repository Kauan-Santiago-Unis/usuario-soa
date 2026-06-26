<?php

namespace App\Http\Controllers;

use App\Models\NivelAcesso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    private function ensureFuncionario(Request $request): void
    {
        abort_unless($request->user() && $request->user()->isFuncionario(), 403, 'Acesso negado.');
    }

    private function ensureOwnerOrFuncionario(Request $request, User $usuario): void
    {
        $auth = $request->user();

        abort_unless($auth, 401, 'Não autenticado.');

        if ($auth->isFuncionario()) {
            return;
        }

        abort_unless((int) $auth->id === (int) $usuario->id, 403, 'Acesso negado.');
    }

    public function index(Request $request)
    {
        $this->ensureFuncionario($request);

        return response()->json(
            User::with('nivelAcesso')->paginate(15)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'max:255', 'unique:usuarios,cpf'],
            'email' => ['required', 'email', 'max:255', 'unique:usuarios,email'],
            'telefone' => ['nullable', 'string', 'max:255'],
            'senha' => ['required', 'string', 'min:6', 'max:255'],
            'data_nascimento' => ['nullable', 'date'],
        ]);

        $nivelCliente = NivelAcesso::where('nome', 'cliente')->firstOrFail();

        $usuario = User::create([
            'nome' => $data['nome'],
            'cpf' => $data['cpf'],
            'email' => $data['email'],
            'telefone' => $data['telefone'] ?? null,
            'senha' => Hash::make($data['senha']),
            'email_verificado' => false,
            'data_verificacao' => null,
            'data_nascimento' => $data['data_nascimento'] ?? null,
            'nivel_acesso_id' => $nivelCliente->id,
        ]);

        return response()->json($usuario->load('nivelAcesso'), 201);
    }

    public function show(Request $request, int $id)
    {
        $usuario = User::with(['nivelAcesso', 'enderecos'])->findOrFail($id);
        $this->ensureOwnerOrFuncionario($request, $usuario);

        return response()->json($usuario);
    }

    public function update(Request $request, int $id)
    {
        $usuario = User::findOrFail($id);
        $this->ensureOwnerOrFuncionario($request, $usuario);

        $data = $request->validate([
            'nome' => ['sometimes', 'required', 'string', 'max:255'],
            'cpf' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('usuarios', 'cpf')->ignore($usuario->id)],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('usuarios', 'email')->ignore($usuario->id)],
            'telefone' => ['nullable', 'string', 'max:255'],
            'senha' => ['nullable', 'string', 'min:6', 'max:255'],
            'data_nascimento' => ['nullable', 'date'],
        ]);

        if (!empty($data['senha'])) {
            $data['senha'] = Hash::make($data['senha']);
        } else {
            unset($data['senha']);
        }

        $usuario->update($data);

        return response()->json($usuario->fresh()->load('nivelAcesso'));
    }

    public function situacao(Request $request, int $id)
    {
        $usuario = User::findOrFail($id);
        $this->ensureOwnerOrFuncionario($request, $usuario);

        $motivos = [
            'cadastro_existe' => true,
            'email_verificado' => (bool) $usuario->email_verificado,
        ];

        return response()->json([
            'apto' => $motivos['cadastro_existe'] && $motivos['email_verificado'],
            'motivos' => $motivos,
        ]);
    }

    public function verificarEmail(Request $request, int $id)
    {
        $this->ensureFuncionario($request);

        $usuario = User::findOrFail($id);
        $usuario->update([
            'email_verificado' => true,
            'data_verificacao' => now(),
        ]);

        return response()->json($usuario);
    }
}
