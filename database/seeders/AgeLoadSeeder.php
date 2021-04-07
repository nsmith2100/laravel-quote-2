<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AgeLoadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dt = date('Y-m-d h:i:s');

        DB::table('age_loads')->insertOrIgnore([
            ['start_age' => 18, 'end_age' => 30, 'load' => 0.6, 'created_at' => $dt],
            ['start_age' => 31, 'end_age' => 40, 'load' => 0.7, 'created_at' => $dt],
            ['start_age' => 41, 'end_age' => 50, 'load' => 0.8, 'created_at' => $dt],
            ['start_age' => 51, 'end_age' => 60, 'load' => 0.9, 'created_at' => $dt],
            ['start_age' => 61, 'end_age' => 70, 'load' => 1, 'created_at' => $dt]
        ]);
    }
}
