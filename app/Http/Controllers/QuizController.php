<?php

namespace App\Http\Controllers;
use App\Models\Question;
use App\Models\Segments;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DB;

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

    public function startQuiz(Request $request)
    {
        $courseId = $request->courseId; // Get course ID from quiz context
        $segmentId = $request->segmentId;// Get segment ID (lesson/module) from context

        // Check student's progress for this course and segment
        $progress = DB::table('progress')
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('segment_id', $segmentId)
            ->first();

        $maxAttempts = 3;
        $cooldownHours = 24;
        
        if ($progress) {
            $lastAttemptTime = Carbon::parse($progress->last_attempt_time);
            $hoursSinceLastAttempt = $lastAttemptTime->diffInHours(Carbon::now());

            if ($progress->attempt_count >= $maxAttempts) {
                return response()->json([
                    'message' => 'Maximum number of attempts reached. You cannot retake the quiz.',
                ], 403);
            }

            if ($hoursSinceLastAttempt < $cooldownHours) {
                $remainingHours = $cooldownHours - $hoursSinceLastAttempt;
                return response()->json([
                    'message' => "You can retake the quiz in $remainingHours hours.",
                ], 403);
            }
        }

        // Fetch quiz questions and return them for the frontend
        $questions = Quiz::find($quizId)->questions;
        return response()->json($questions);
    }

    public function finishQuiz(Request $request)
    {
        $studentId = $request->student_id;
        $courseId = $request->courseId; // Get course ID from quiz context
        $segmentId = $request->segmentId; // Get segment ID (lesson/module) from context

        // Fetch or create progress for this student, course, and segment
        $progress = DB::table('progress')
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('segment_id', $segmentId)
            ->first();

        if (!$progress) {
            // Create new progress entry
            DB::table('progress')->insert([
                'student_id' => $studentId,
                'course_id' => $courseId,
                'segment_id' => $segmentId,
                'attempt_count' => 1,
                'last_attempt_time' => now(),
            ]);
        } else {
            // Update progress for additional attempts
            DB::table('progress')
                ->where('student_id', $studentId)
                ->where('course_id', $courseId)
                ->where('segment_id', $segmentId)
                ->update([
                    'attempt_count' => $progress->attempt_count + 1,
                    'last_attempt_time' => now(),
                ]);
        }

        // Optionally calculate score and return
        $score = $this->calculateScore($request->selectedAnswers);
        return response()->json(['score' => $score]);
    }
}
