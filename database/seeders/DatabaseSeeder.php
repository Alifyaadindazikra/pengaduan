<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            //bcrypt biar terenskripsi si pw
            //enskripsi biar masuk db data palsu( random) tidak tau pw
            
            
        ]);
        User::create([
            'name' => 'Petugas',
            'email' => 'petugas@gmail.com',
            'password' => bcrypt('petugas'),
            'role'=> 'petugas',
            //bcrypt biar terenskripsi si pw
            //enskripsi biar masuk db data palsu( random) tidak tau pw
            
            
        ]);
    }
}
