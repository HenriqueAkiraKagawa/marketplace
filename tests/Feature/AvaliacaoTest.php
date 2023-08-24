<?php
//$debug //dd($data) //dd($response);
namespace Tests\Feature;

use App\Models\Produto;
use App\Models\Avaliacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AvaliacaoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

   /**Listar todos os avaliacao
     * @return void
     */

     public function testListarTodosAvaliacao()
    {
         //Criar 5 avaliacao
         //Salvar Temporario
         Avaliacao::factory()->count(5)->create();
 
         // usar metodo GET para verificar o retorno
         $resposta = $this->getJson('/api/avaliacaos');
              
         //Testar ou verificar saida
         $resposta->assertStatus(200)
             ->assertJsonCount(5, 'data')
             ->assertJsonStructure([
                 'data' => [
                     '*' => ['id', 'descricao', 'nota','produto_id', 'created_at', 'updated_at',]
                ]
            ]);
    }
    /** 
     * Criar um avaliacao
     */
    public function testCriarAvaliacaoSucesso(){

        // Criar uma produto usando o factory
        $produto = Produto::factory()->create();

        //Criar o objeto
        $data = [
            'descricao' => $this->faker->sentence(),
            'nota' => $this->faker->numberBetween($int1 = 0, $int2 = 5),
            'produto_id' =>$produto->id
        ];

        // Fazer uma requisição POST
        $response = $this->postJson('/api/avaliacaos', $data);

        // Verifique se teve um retorno 201 - Criado com Sucesso e se a estrutura do JSON Corresponde
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'descricao', 'nota', 'produto_id', 'created_at', 'updated_at']);
    }
    /**
     * Teste de criação com falhas
     *
     * @return void
     */
    public function testCriarAvaliacaoFalha(){
        $data = [
            "descricao" => 'a'
        ];
         // Fazer uma requisição POST
        $response = $this->postJson('/api/avaliacaos', $data);

        // Verifique se teve um retorno 422 - Falha no salvamento
        // e se a estrutura do JSON Corresponde
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['descricao']);
    }
        /**
     * Teste de deletar com sucesso
     *
     * @return void
     */
    
    public function testDeleteAvaliacao()
    {
        // Criar avaliacao fake
        $avaliacao = avaliacao::factory()->create();

        // enviar requisição para Delete
        $response = $this->deleteJson('/api/avaliacaos/' . $avaliacao->id);

        // Verifica o Delete
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Avaliação deletado com sucesso!'
            ]);

        //Verifique se foi deletado do banco
        $this->assertDatabaseMissing('avaliacaos', ['id' => $avaliacao->id]);
    }
    /**
     * Teste remoção de registro inexistente
     *
     * @return void
     */
    public function testDeleteAvaliacaoNaoExistente()
    {
        // enviar requisição para Delete
        $response = $this->deleteJson('/api/avaliacaos/999');

        // Verifique a resposta
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Avaliação não encontrado!'
            ]);
    }
}