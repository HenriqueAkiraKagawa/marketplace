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

    /**
     *Teste de update com sucesso
     *
     * @return void
     */
    public function testUpdateProdutoSucesso()
    {
        // Crie um produto fake
        $produto = Produto::factory()->create();

        // Dados para update
        $newData = [
            'nome' => 'Novo nome',
            'descricao' => 'Novo nome',
            'preco' => 3.55,
            'estoque' => 5,
            'tipo_id' => $produto->tipo->id

        ];

        // Faça uma chamada PUT
        $response = $this->putJson('/api/produtos/' . $produto->id, $newData);

        // Verifique a resposta
        $response->assertStatus(200)
            ->assertJson([
                'id' => $produto->id,
                'nome' => 'Novo nome',
                'descricao' => 'Novo nome',
                'preco' => 3.55,
                'estoque' => 5,
                'tipo_id' => $produto->tipo->id
            ]);
    }
    /**
     * Testando com falhas
     *
     * @return void
     */
    public function testUpdateProdutoDataInvalida()
    {
        // Crie um produto falso
        $produto = produto::factory()->create();

        // Crie dados falhos
        $invalidData = [
            'descricao' => '', // Invalido: Descricao vazio
        ];

        // faça uma chamada PUT
        $response = $this->putJson('/api/produtos/' . $produto->id, $invalidData);

        // Verificar se teve um erro 422
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['descricao']);
    }
     /**
     * Teste update de produto
     *
     * @return void
     */
    public function testUpdateProdutoNaoExistente()
    {
        $produto = produto::factory()->create();

        $newData =  [
            'nome' => 'Novo nome',
            'descricao' => 'Novo nome',
            'preco' => 3.55,
            'estoque' => 5,
            'tipo_id' => $produto->tipo->id

        ];

        // Faça uma chamada para um id falho
        $response = $this->putJson('/api/produtos/9999', $newData); //O 999 não deve existir

        // Verificar o retorno 404
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Produto não encontrado'
            ]);
    }
    /**
     * Teste de update com os mesmos dados
     *
     * @return void
     */
    public function testUpdateProdutoMesmosDados()
    {
        // Crie um produto fake
        $produto = Produto::factory()->create();

        // Data para update
        $sameData = [
                'nome' => 'Novo nome',
                'descricao' => 'Novo nome',
                'preco' => 3.55,
                'estoque' => 5,
                'tipo_id' => $produto->tipo->id           
        ];

        // Faça uma chamada PUT
        $response = $this->putJson('/api/produtos/' . $produto->id, $sameData);

        // Verifique a resposta
        $response->assertStatus(200)
            ->assertJson([
                    'nome' => 'Novo nome',
                    'descricao' => 'Novo nome',
                    'preco' => 3.55,
                    'estoque' => 5,
                    'tipo_id' => $produto->tipo->id
            ]);
    }
    public function testUpdateProdutoNomeDuplicada()
    {
        // Crie dois produtos fakes
        $PExistente = produto::factory()->create();
        $PUpdate = produto::factory()->create();

        // update
        $sameData = [
            'nome' => $PExistente->nome,
            'descricao' => $PExistente->descricao,
            'preco' => $PExistente->preco,
            'estoque' => $PExistente->estoque,
            'tipo_id' => $PExistente->tipo->id            
        ];

        // Faça o put 
        $response = $this->putJson('/api/produtos/' . $PUpdate->id, $sameData);

        // Verifique a resposta
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nome']);
    }
    /**
     * Teste de deletar com sucesso
     *
     * @return void
     */
    public function testDeleteProduto()
    {
        // Criar produto fake
        $produto = produto::factory()->create();

        // enviar requisição para Delete
        $response = $this->deleteJson('/api/produtos/' . $produto->id);

        // Verifica o Detele
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'produto deletado com sucesso!'
            ]);

        //Verifique se foi deletado do banco
        $this->assertDatabaseMissing('produtos', ['id' => $produto->id]);
    }

    /**
     * Teste remoção de registro inexistente
     *
     * @return void
     */
    public function testDeleteProdutoNaoExistente()
    {
        // enviar requisição para Delete
        $response = $this->deleteJson('/api/tipos/999');

        // Verifique a resposta
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Tipo não encontrado!'
            ]);
    }



  

}