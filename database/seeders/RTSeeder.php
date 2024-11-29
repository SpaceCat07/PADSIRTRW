<?php

namespace Database\Seeders;

use App\Models\RTModel;
use App\Models\RWModel;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RTSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $listRW = RWModel::pluck('id') -> toArray();

        RTModel::create([
            'id_rw' => $faker -> randomElement($listRW),
            'nama_rt' => $faker->unique()->name(),
            'nomor_rekening' => $faker->numerify('################')
        ]);
    }
}
