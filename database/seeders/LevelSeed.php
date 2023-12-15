<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Level::create(['label' => 'Enfant']);
        Level::create(['label' => 'A1']);
        Level::create(['label' => 'A2']);
        Level::create(['label' => 'B1']);
        Level::create(['label' => 'B2']);
        Level::create(['label' => 'C1']);
        Level::create(['label' => 'C2']);
    }
}
