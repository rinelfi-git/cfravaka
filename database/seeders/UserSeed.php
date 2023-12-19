<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // User::create([
        //     'username' => 'rinelfi',
        //     'name'     => 'Rijaniaina Elie Fidèle',
        //     'email'    => 'elierijaniaina@gmail.com',
        //     'password' => Hash::make("c'est devenu difficile")
        // ]);
        User::create([
            'username' => 'rantoarijaona',
            'name'     => 'Andriatsizehena Ranto Arijaona',
            'email'    => 'rantoarijaona@gmail.com',
            'password' => Hash::make("rantoarijaona")
        ]);
    }
}
