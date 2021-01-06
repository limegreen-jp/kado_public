<?php

namespace Database\Factories;

use App\Models\MonthPrice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\KadoController;

class MonthPriceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MonthPrice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->getDate();
        $term_id = $this->getTermId($date);

        return [
            'project_id' => $this->faker->numberBetween($min = 1, $max = 100),
            'date' => $date,
            'term_id' => $term_id,
            'working_time' => $this->faker->numberBetween($min = 0, $max = 5),
            'price' => $this->faker->numberBetween($min = 10, $max = 5),
        ];
    }

    private function getDate() {
        $year = rand(2019, 2021);
        $month = rand(1, 12);

        return $year.'/'.$month;
    }

    private function getTermId($date) {
        $kadoController = new KadoController;
        $term = $kadoController->convertYearMonthIntoTeam($date);
        $term_id = DB::table('terms')
                        ->select('id')
                        ->where('term_name', '=', $term)
                        ->get();

                        
        return $term_id[0]->id;
    }
}
