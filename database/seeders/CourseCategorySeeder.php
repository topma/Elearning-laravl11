<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            ['category_name' => 'Data Science', 'category_status' => 1],
            ['category_name' => 'Programming and Development', 'category_status' => 1],
            ['category_name' => 'Business', 'category_status' => 1],
            ['category_name' => 'Personal Development', 'category_status' => 1],
            ['category_name' => 'Design', 'category_status' => 1],  
            ['category_name' => 'Information Technology', 'category_status' => 1],           
        ];

        DB::table('course_categories')->insert($categories);
    }
}
