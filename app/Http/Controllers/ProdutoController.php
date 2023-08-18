<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProdutoRequest;
use App\Http\Requests\UpdateProdutoRequest;
use App\Models\Tipo;

class ProdutoController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Pegar a lista do banco
        $produtos = Produto::all();

        //retorna lista em json
        return response()->json(['data'=>$produtos]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProdutoRequest $request)
    {
        // Crie um novo produto
        $produto = Produto::create($request->all());

        // Retorne o produto e o code 201
        return response()->json($produto, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // procure produto por id
        $produto = Produto::find($id);

        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        return response()->json($produto);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdutoRequest $request, Produto $id)
    {
        $produto = Produto::find($id);

        if (!$produto) {
            return response()->json(['message' => 'Tipo não encontrado'], 404);
        }

        // Faça o update do produto
        $produto->update($request->all());

        // Retorne o produto
        return response()->json($produto);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produto $id)
    {
        // Encontre um produto pelo id
        $produto = Produto::find($id);

        if (!$produto) {
            return response()->json(['message' => 'produto não encontrado!'], 404);
        }  
  
        // Delete the brand
        $produto->delete();

        return response()->json(['message' => 'produto deletado com sucesso!'], 200);
    }
}
