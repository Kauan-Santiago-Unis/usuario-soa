<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NivelAcesso;

class NivelAcessoController extends Controller
{
    // LISTAR TODOS
    public function index()
    {
        return response()->json(NivelAcesso::all());
    }

    // CRIAR NOVO NIVEL
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'permissoes' => 'nullable|array',
        ]);

        $nivel = NivelAcesso::create([
            'nome' => $request->nome,
            'permissoes' => $request->permissoes ?? [],
        ]);

        return response()->json($nivel, 201);
    }

    // MOSTRAR UM
    public function show($id)
    {
        return response()->json(NivelAcesso::findOrFail($id));
    }

    // ATUALIZAR
    public function update(Request $request, $id)
    {
        $nivel = NivelAcesso::findOrFail($id);

        $nivel->update([
            'nome' => $request->nome ?? $nivel->nome,
            'permissoes' => $request->permissoes ?? $nivel->permissoes,
        ]);

        return response()->json($nivel);
    }

    // DELETAR
    public function destroy($id)
    {
        $nivel = NivelAcesso::findOrFail($id);
        $nivel->delete();

        return response()->json(['message' => 'Deletado com sucesso']);
    }
}