<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\KadoController;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_name' => $this->faker->name,
            'project_name' => $this->faker->realText(20),
            'skill_id' => $this->faker->randomElement([1, 2, 3, 4]),
            'level_id' => $this->faker->randomElement([1, 2, 3]),
            'user_id' => $this->faker->randomElement([10, 11, null]),
            'description' => $this->faker->realText(50),
        ];
    }
}
