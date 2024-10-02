<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseCategory;

class SearchCourseController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve all categories and difficulties
        $category = CourseCategory::get();
        
        // Retrieve selected categories and difficulties from the request
        $selectedCategories = $request->input('categories', []);
        $selectedDifficulty = $request->input('difficulty', []);

        // Filter courses based on selected categories and difficulty
        $course = Course::where('status', 2)
            ->when($selectedCategories, function ($query) use ($selectedCategories) {
                $query->whereIn('course_category_id', $selectedCategories);
            })
            ->when($selectedDifficulty, function ($query) use ($selectedDifficulty) {
                $query->whereIn('difficulty', $selectedDifficulty);
            })
            ->paginate(10);
        
        $difficulty_beginner = Course::where('difficulty', 'Beginner')->get();
        $difficulty_intermediate = Course::where('difficulty', 'Intermediate')->get();
        $difficulty_advanced = Course::where('difficulty', 'Advanced')->get();

        $allCourse = Course::where('status', 2)->get();        

        return view('frontend.searchCourse', compact('course', 'category', 'selectedCategories', 
        'selectedDifficulty', 'allCourse','difficulty_beginner', 'difficulty_advanced','difficulty_intermediate'));
    }

    public function courseName($courseCategory)
    {
        // Retrieve all categories and difficulties
        $category = CourseCategory::get();
        $selectedCategories = $courseCategory;
        $selectedDifficulty = "Beginner";

        // Get the category by its name and extract the ID
        $categoryId = CourseCategory::where('category_name', $courseCategory)->value('id');
        
        if (!$categoryId) {
            // Handle the case when the category is not found
            abort(404, 'Course Category not found');
        }

        // Filter courses based on the category ID and difficulty
        $course = Course::where('status', 2)
            ->where('course_category_id', $categoryId)
            ->paginate(10);

        $difficulty_beginner = Course::where('difficulty', 'Beginner')->get();
        $difficulty_intermediate = Course::where('difficulty', 'Intermediate')->get();
        $difficulty_advanced = Course::where('difficulty', 'Advanced')->get();

        $allCourse = Course::where('status', 2)->get();        

        return view('frontend.courseCategory', compact('course', 'category', 'allCourse', 'selectedCategories'
        ,'selectedDifficulty','difficulty_beginner', 'difficulty_advanced','difficulty_intermediate'));
    }


}
