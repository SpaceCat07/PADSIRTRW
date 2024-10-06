<?php

namespace Database\Seeders;

use App\Models\Warga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    for ($i=0; $i < 2; $i++) { 
        Warga::create([
            'nama' => fake()->name(),
            'email' => fake()->email(),
            'no_hp' => '08'.fake()->numerify('##########'),
            'username' => fake()->userName(),
            'password' => bcrypt(fake()->password()), // generate a random password
            'aktivasi' => fake()->randomElement(['Activated', 'Unactivated']),
        ]);
    }
}
}
