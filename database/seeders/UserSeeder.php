<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = [
            [
                'name_en' => 'Femi Akinyooye', 
                'contact_en' => '23409073829919',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => 1,
                'language' => 'en',
                'image' => 'femi_akinyooye.jpg',
                'full_access' => 1,
                'status' => 1,                
        ],
                      
        ];

        DB::table('users')->insert($users);
    }
}
