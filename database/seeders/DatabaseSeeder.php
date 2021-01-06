<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();

        $this->call([
            SkillSeeder::class,
            TermSeeder::class,
            LevelSeeder::class,
            LevelSkillSeeder::class,
        ]);

        \App\Models\Project::factory(100)->create();
        \App\Models\MonthPrice::factory(1000)->create();
    }
}
