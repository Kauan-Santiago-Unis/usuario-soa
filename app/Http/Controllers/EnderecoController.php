<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    public function index(Request $request, $usuario_id)
    {
        if ($request->user()->id != $usuario_id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        $enderecos = Endereco::where('usuario_id', $usuario_id)->get();
        
        return response()->json($enderecos, 200);
    }

    public function store(Request $request, $usuario_id)
    {
        if ($request->user()->id != $usuario_id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        $validated = $request->validate([
            'logradouro'  => 'required|string|max:255',
            'numero'      => 'required|string|max:255',
            'cep'         => 'required|string|max:255',
            'cidade'      => 'required|string|max:255',
            'estado'      => 'required|string|max:255',
            'complemento' => 'nullable|string|max:255',
        ]);

        $validated['usuario_id'] = $usuario_id;
        
        $endereco = Endereco::create($validated);

        return response()->json($endereco, 201);
    }

    public function update(Request $request, $id)
    {
        $endereco = Endereco::findOrFail($id);

        if ($request->user()->id != $endereco->usuario_id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        $validated = $request->validate([
            'logradouro'  => 'sometimes|required|string|max:255',
            'numero'      => 'sometimes|required|string|max:255',
            'cep'         => 'sometimes|required|string|max:255',
            'cidade'      => 'sometimes|required|string|max:255',
            'estado'      => 'sometimes|required|string|max:255',
            'complemento' => 'nullable|string|max:255',
        ]);

        $endereco->update($validated);

        return response()->json($endereco, 200);
    }

    public function destroy(Request $request, $id)
    {
        $endereco = Endereco::findOrFail($id);

        if ($request->user()->id != $endereco->usuario_id) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        $endereco->delete();

        return response()->json(null, 204);
    }
}