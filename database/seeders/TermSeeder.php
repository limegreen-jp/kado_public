<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=24; $i<=30; $i++) {
            for ($j=1; $j<=2; $j++) {                
                DB::table('terms')->insert([
                    array('term_name' => $i.'Y'.$j.'H')
                ]);
            }
        }
    }
}
