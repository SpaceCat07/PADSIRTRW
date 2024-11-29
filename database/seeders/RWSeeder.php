<?php

namespace Database\Seeders;

use App\Models\RWModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RWSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        RWModel::create([
            'nama_rw' => $faker -> unique() ->  name(),
            'nomer_rekening' => $faker -> numerify('###############')
        ]);
    }
}
