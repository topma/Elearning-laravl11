<?php

namespace App\Http\Controllers;
use App\Models\Question;
use App\Models\Segments;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    //

    public function getQuestions($quizId)
    {
        $questions = Question::where('quiz_id', $quizId)
        ->get(['id', 'content', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer']);

        return response()->json($questions);
    }

    public function saveAnswer(Request $request)
    {
        try {
            // Log the request input for debugging
            // Log::info('Save answer request received', [
            //     'student_id' => $request->student_id,
            //     'question_id' => $request->question_id,
            //     'answer' => $request->answer
            // ]);

            // Your logic for saving the answer goes here
            $studentId = $request->input('student_id');
            $questionId = $request->input('question_id');
            $answer = $request->input('answer');

            // Save to the database (replace with your model logic)
            // For example:
            Answer::updateOrCreate(
                ['student_id' => $studentId, 'question_id' => $questionId],
                ['answer' => $answer]
            );

            // Log success message
            // Log::info('Answer saved successfully', [
            //     'student_id' => $studentId,
            //     'question_id' => $questionId,
            //     'answer' => $answer
            // ]);

            return response()->json(['message' => 'Answer saved successfully'], 200);
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error saving answer', [
                'error' => $e->getMessage(),
                'student_id' => $request->student_id,
                'question_id' => $request->question_id,
                'answer' => $request->answer
            ]);

            return response()->json(['error' => 'Failed to save the answer'], 500);
        }
    }

    public function updateProgress(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'course_id' => 'required|integer',
            'segment_id' => 'required|integer',
            'score' => 'required|numeric',
            'quiz_pass_mark' => 'required|numeric',
            'max_attempts' => 'required|integer'
        ]);

        $studentId = $validated['student_id'];
        $courseId = $validated['course_id'];
        $segmentId = $validated['segment_id'];
        $score = $validated['score'];
        $quizPassMark = $validated['quiz_pass_mark'];
        $maxAttempts = $validated['max_attempts'];

        // Retrieve the current progress record
        $progress = Progress::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('segment_id', $segmentId)
            ->first();

        if ($progress) {
            if ($score >= $quizPassMark) {
                // Update to completed
                $progress->completed = 1;
                $progress->quiz_attempt = 1; // Set attempts to 1
            } else {
                // Increment the quiz_attempt
                $progress->quiz_attempt += 1;

                // Check if the user has reached the maximum attempts
                if ($progress->quiz_attempt >= $maxAttempts) {
                    // Logic to handle the 24-hour wait can be added here
                    return response()->json(['message' => 'You have reached the maximum number of attempts. Please try again later.'], 403);
                }
            }
            $progress->save();
        } else {
            // Create a new record if no progress exists
            Progress::create([
                'student_id' => $studentId,
                'course_id' => $courseId,
                'segment_id' => $segmentId,
                'completed' => $score >= $quizPassMark ? 1 : 0,
                'quiz_attempt' => $score >= $quizPassMark ? 1 : 1 // Attempt count
            ]);
        }

        return response()->json(['message' => 'Progress updated successfully.']);
    }
}
