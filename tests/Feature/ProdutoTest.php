<?php

namespace Tests\Feature;

use App\Models\Produto;
use App\Models\Tipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProdutoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

   /**Listar todos os Produto
     * @return void
     */

     public function testListarTodosProduto()
    {
         //Criar 5 Produto
         //Salvar Temporario
         Produto::factory()->count(5)->create();
 
         // usar metodo GET para verificar o retorno
         $resposta = $this->getJson('/api/produtos');
              
         //Testar ou verificar saida
         $resposta->assertStatus(200)
             ->assertJsonCount(5, 'data')
             ->assertJsonStructure([
                 'data' => [
                     '*' => ['id', 'descricao', 'preco', 'estoque', 'created_at', 'updated_at', 'tipo_id',]
                ]
            ]); 
            //dd($response);
    }
    /**
     * Criar um Produto
     */
    public function testCriarProdutoSucesso(){

        // Criar um tipo usando o factory
        $tipo = tipo::factory()->create();

        //Criar o objeto
        $data = [
            'nome' => "". $this->faker->word." ".
                $this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'descricao' => $this->faker->sentence(),
            'preco' => $this->faker->randomFloat(2, 10, 1000),
            'estoque' => $this->faker->numberBetween($int1 = 0, $int2 = 99999),
            'tipo_id' => $tipo->id
        ];
        //$debug //dd($data) //dd($response);
        // Fazer uma requisição POST
        $response = $this->postJson('/api/produtos', $data);

        // Verifique se teve um retorno 201 - Criado com Sucesso e se a estrutura do JSON Corresponde
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'nome', 'descricao', 'preco', 'estoque', 'tipo_id', 'created_at', 'updated_at']);
    }


/**
     * Teste de criação com falhas
     *
     * @return void
     */
    public function testCriacaoProdutoFalha(){
        $data = [
            "descricao" => 'a'
        ];
         // Fazer uma requisição POST
        $response = $this->postJson('/api/produtos', $data);

        // Verifique se teve um retorno 422 - Falha no salvamento
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['descricao']);
    }
    /**
     * Teste de pesquisa de registro
     *
     * @return void
     */
    public function testPesquisaProdutoSucesso()
    {
        // Criar um tipo
        $produto = Produto::factory()->create();
        
        // Fazer pesquisa
        $response = $this->getJson('/api/produtos/' . $produto->id); 

        // Verificar saida
        $response->assertStatus(200)
            ->assertJson([
                'id' => $produto->id,
                'nome' => $produto->nome,
                'descricao' => $produto->descricao,
                'preco' => $produto->preco,
                'estoque' => $produto->estoque,
                'tipo_id' => $produto->tipo_id,
                
            ]);
    }
    /**
     * Teste de pesquisa de registro com falha
     *
     * @return void
     */
    public function testPesquisaProdutoComFalha()
    {
        // Fazer pesquisa com um id inexistente
        $response = $this->getJson('/api/produtos/999'); // o 999 nao pode existir

        // Veriicar a resposta
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Produto não encontrado'
            ]);
    }
}