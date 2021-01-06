<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('skills')->insert([
            [
                'skill_name' => '構築Dir',
            ],
            [
                'skill_name' => '運用Dir',
            ],
            [
                'skill_name' => 'Des',
            ],
            [
                'skill_name' => 'ME',
            ],
            [
                'skill_name' => 'AD',
            ],
            [
                'skill_name' => 'FE',
            ],
            [
                'skill_name' => 'SE',
            ],
            [
                'skill_name' => 'PG',
            ],
            [
                'skill_name' => 'PM・運用統括',
            ],
            [
                'skill_name' => 'PL・AN',
            ],
            [
                'skill_name' => 'PfM',
            ],
        ]);
    }
}
