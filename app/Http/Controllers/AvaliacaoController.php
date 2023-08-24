<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAvaliacaoRequest;
use App\Http\Requests\UpdateAvaliacaoRequest;

class AvaliacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Pegar a lista do banco
        $avaliacaos = Avaliacao::all();

        //retorna lista em json
        return response()->json(['data'=>$avaliacaos]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAvaliacaoRequest $request)
    {
        // Crie um novo avaliacao
        $avaliacao = Avaliacao::create($request->all());

        // Retorne o avaliacao e o code 201
        return response()->json($avaliacao, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Encontre um produto pelo id
        $avaliacao = Avaliacao::find($id);

        if (!$avaliacao) {
            return response()->json(['message' => 'Avaliação não encontrado!'], 404);
        }  
          
        // Delete the brand
        $avaliacao->delete();
        
        return response()->json(['message' => 'Avaliação deletado com sucesso!'], 200);
    }
}
