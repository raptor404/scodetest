<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SenatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0; $i < 10; $i++){
            DB::table('senator')->insert([
                'first_name' => Str::random(10),
                'last_name' => Str::random(10),
                'email' => 'nrbhawk+'.$i.'@gmail.com',
            ]);
        }

    }
}
