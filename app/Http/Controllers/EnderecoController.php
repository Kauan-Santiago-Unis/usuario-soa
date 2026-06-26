<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\User;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    private function ensureOwnerOrFuncionarioByUser(Request $request, User $usuario): void
    {
        $auth = $request->user();

        abort_unless($auth, 401, 'Não autenticado.');

        if ($auth->isFuncionario()) {
            return;
        }

        abort_unless((int) $auth->id === (int) $usuario->id, 403, 'Acesso negado.');
    }

    private function ensureOwnerOrFuncionarioByEndereco(Request $request, Endereco $endereco): void
    {
        $auth = $request->user();

        abort_unless($auth, 401, 'Não autenticado.');

        if ($auth->isFuncionario()) {
            return;
        }

        abort_unless((int) $auth->id === (int) $endereco->usuario_id, 403, 'Acesso negado.');
    }

    public function index(Request $request, int $usuario_id)
    {
        $usuario = User::findOrFail($usuario_id);
        $this->ensureOwnerOrFuncionarioByUser($request, $usuario);

        $enderecos = Endereco::where('usuario_id', $usuario_id)->get();

        return response()->json($enderecos, 200);
    }

    public function store(Request $request, int $usuario_id)
    {
        $usuario = User::findOrFail($usuario_id);
        $this->ensureOwnerOrFuncionarioByUser($request, $usuario);

        $validated = $request->validate([
            'logradouro' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:255'],
            'cep' => ['required', 'string', 'max:255'],
            'cidade' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'string', 'max:255'],
            'complemento' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['usuario_id'] = $usuario_id;

        $endereco = Endereco::create($validated);

        return response()->json($endereco, 201);
    }

    public function update(Request $request, int $id)
    {
        $endereco = Endereco::findOrFail($id);
        $this->ensureOwnerOrFuncionarioByEndereco($request, $endereco);

        $validated = $request->validate([
            'logradouro' => ['sometimes', 'required', 'string', 'max:255'],
            'numero' => ['sometimes', 'required', 'string', 'max:255'],
            'cep' => ['sometimes', 'required', 'string', 'max:255'],
            'cidade' => ['sometimes', 'required', 'string', 'max:255'],
            'estado' => ['sometimes', 'required', 'string', 'max:255'],
            'complemento' => ['nullable', 'string', 'max:255'],
        ]);

        $endereco->update($validated);

        return response()->json($endereco, 200);
    }

    public function destroy(Request $request, int $id)
    {
        $endereco = Endereco::findOrFail($id);
        $this->ensureOwnerOrFuncionarioByEndereco($request, $endereco);

        $endereco->delete();

        return response()->noContent();
    }
}
