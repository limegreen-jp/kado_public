<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('level_skills')->insert([
            [
                'skill_id' => 1,
                'level_id' => 1,
                'unit_price' => 150,
            ],
            [
                'skill_id' => 1,
                'level_id' => 2,
                'unit_price' => 120,
            ],
            [
                'skill_id' => 1,
                'level_id' => 3,
                'unit_price' => 90,
            ],
            [
                'skill_id' => 2,
                'level_id' => 1,
                'unit_price' => 120,
            ],
            [
                'skill_id' => 2,
                'level_id' => 2,
                'unit_price' => 90,
            ],
            [
                'skill_id' => 2,
                'level_id' => 3,
                'unit_price' => 70,
            ],
            [
                'skill_id' => 3,
                'level_id' => 1,
                'unit_price' => 120,
            ],
            [
                'skill_id' => 3,
                'level_id' => 2,
                'unit_price' => 90,
            ],
            [
                'skill_id' => 3,
                'level_id' => 3,
                'unit_price' => 60,
            ],
            [
                'skill_id' => 4,
                'level_id' => 1,
                'unit_price' => 120,
            ],
            [
                'skill_id' => 4,
                'level_id' => 2,
                'unit_price' => 80,
            ],
            [
                'skill_id' => 4,
                'level_id' => 3,
                'unit_price' => 60,
            ],
            [
                'skill_id' => 5,
                'level_id' => 1,
                'unit_price' => 180,
            ],
            [
                'skill_id' => 5,
                'level_id' => 2,
                'unit_price' => 150,
            ],
            [
                'skill_id' => 6,
                'level_id' => 1,
                'unit_price' => 180,
            ],
            [
                'skill_id' => 6,
                'level_id' => 2,
                'unit_price' => 150,
            ],
            [
                'skill_id' => 7,
                'level_id' => 1,
                'unit_price' => 180,
            ],
            [
                'skill_id' => 7,
                'level_id' => 2,
                'unit_price' => 150,
            ],
            [
                'skill_id' => 8,
                'level_id' => 1,
                'unit_price' => 120,
            ],
            [
                'skill_id' => 8,
                'level_id' => 2,
                'unit_price' => 100,
            ],
            [
                'skill_id' => 8,
                'level_id' => 3,
                'unit_price' => 70,
            ],
            [
                'skill_id' => 9,
                'level_id' => 1,
                'unit_price' => 200,
            ],
            [
                'skill_id' => 9,
                'level_id' => 2,
                'unit_price' => 180,
            ],
            [
                'skill_id' => 9,
                'level_id' => 3,
                'unit_price' => 100,
            ],
            [
                'skill_id' => 10,
                'level_id' => 1,
                'unit_price' => 200,
            ],
            [
                'skill_id' => 10,
                'level_id' => 2,
                'unit_price' => 150,
            ],
            [
                'skill_id' => 10,
                'level_id' => 3,
                'unit_price' => 90,
            ],
            [
                'skill_id' => 11,
                'level_id' => 2,
                'unit_price' => 110,
            ],
        ]);
    }
}
