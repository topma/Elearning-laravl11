<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Event;
use App\Models\Instructor;
use App\Models\CourseCategory;

class HomeController extends Controller
{
    public function index()
    {
        $course = Course::get();
        $event = Event::orderBy('date', 'Desc')->get();
        $instructor = Instructor::get();
        $category = CourseCategory::get();
        $popularCourses = Course::where('tag', 'popular')->get();

        $designCategories = CourseCategory::whereIn('category_name', ['Graphics Desgin', 'Web Design', 'Video Editing'])->pluck('id')->toArray();
        $designCourses = Course::whereIn('course_category_id', $designCategories)->where('tag', 'popular')->get();

        $developmentCategories = CourseCategory::whereIn('category_name', ['Web Development', 'Mobile Development', 'Game Development', 'Database Design & Development'])->pluck('id')->toArray();
        $developmentCourses = Course::whereIn('course_category_id', $developmentCategories)->where('tag', 'popular')->get();

        $dataCategories = CourseCategory::whereIn('category_name', ['Data Science'])->pluck('id')->toArray();
        $dataCourses = Course::whereIn('course_category_id', $dataCategories)->where('tag', 'popular')->get();

        $salesCategories = CourseCategory::whereIn('category_name', ['Digital Marketing', 'Social Media Manager', 'Content Creation', 'Social Media Marketing', 'Copywriting'])->pluck('id')->toArray();
        $salesCourses = Course::whereIn('course_category_id', $salesCategories)->where('tag', 'popular')->get();

        $businessCategories = CourseCategory::whereIn('category_name', ['Digital Marketing', 'Entrepreneurship'])->pluck('id')->toArray();
        $businessCourses = Course::whereIn('course_category_id', $businessCategories)->where('tag', 'popular')->get();

        $itCategories = CourseCategory::whereIn('category_name', ['Hardware', 'Network Technology', 'Software & Security', 'Operating System & Server', '2D Animation', '3D Animation'])->pluck('id')->toArray();
        $itCourses = Course::whereIn('course_category_id', $itCategories)->where('tag', 'popular')->get();

        return view(
            'frontend.home',
            compact('course', 'instructor', 'category', 'popularCourses', 'designCourses', 'developmentCourses', 'businessCourses', 'itCourses'
            ,'salesCourses', 'dataCourses','event')
        );
    }

    public function signUpForm()
    {
        return view('frontend.signup');
    }

    public function about()
    {
        $instructor = Instructor::get();

        return view('frontend.about', compact('instructor'));
    }
    public function contact()
    {
        $instructor = Instructor::get();

        return view('frontend.contact', compact('instructor'));
    }
}
