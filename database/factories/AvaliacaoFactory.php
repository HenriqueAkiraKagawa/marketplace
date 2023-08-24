<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Produto;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Avaliacao>
 */
class AvaliacaoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'descricao' => $this->faker->sentence(),
            'nota' => $this->faker->numberBetween($int1 = 0, $int2 = 5),
          //'nota' => $this->faker->randomFloat(2, 0, 10),
            'produto_id' => function () {
                return produto::factory()->create()->id;
            }
        ];
    }
}
