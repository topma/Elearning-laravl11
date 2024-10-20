<?php

namespace App\Http\Controllers\Backend\Reviews;

use App\Models\Review;
use App\Models\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $review=Review::paginate(20);
        return view('backend.review.index', compact('review'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        
    }
    public function saveReviews(Request $request)
    {
        // Log::info('Review Store Request:', [
        //     'request_data' => $request->all(),
        //     'user_ip' => $request->ip(),
        //     'user_agent' => $request->userAgent(),
        //     'timestamp' => now(),
        // ]);

         // Validate the input
        $request->validate([
            'comment' => 'required|string|max:1000',
            'student_id' => 'required|integer|exists:users,id',
            'course_id' => 'required|integer|exists:courses,id',
        ]);      

        // Save the review
        $review = Review::updateOrCreate(
            ['course_id' => $request->course_id, 'student_id' => $request->student_id],
            ['comment' => $request->comment]
        );

        // Return a JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Comment posted successfully',
        ]);
    }

    public function getReviews($courseId)
    {
        // Eager load the course with reviews count
        $course = Course::withCount('reviews')->find($courseId);

        // Check if course exists
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found',
            ], 404);
        }

        // Fetch the reviews for the course
        $reviews = Review::where('course_id', $courseId)->latest()->get();

        // Render the reviews as HTML to return via AJAX
        $html = view('partials.reviews', compact('reviews'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $course->reviews_count,
        ]);
    }

    public function storeRating(Request $request)
    {
        // Validate input
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',           
            'course_id' => 'required|exists:courses,id',
            'student_id' => 'required|exists:users,id',
        ]);

        // Store the review in the database
        $review = Review::updateOrCreate(
            ['course_id' => $request->course_id, 'student_id' => $request->student_id],
            ['rating' => $request->rating]
        );

        return response()->json(['success' => true, 
        'message' => 'Rating submitted successfully!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
