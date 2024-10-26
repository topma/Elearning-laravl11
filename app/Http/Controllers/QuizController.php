<?php

namespace App\Http\Controllers;
use App\Models\Question;
use App\Models\Segments;
use App\Models\Answer;
use App\Models\Progress;
use App\Models\Quiz;
use App\Models\Lesson;
use App\Models\ProgressAll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DB;

class QuizController extends Controller
{
    //

    public function getQuestions($quizId)
    {
        $studentId = currentUserId();
        $quiz = Quiz::where('id', $quizId)->first();
        $courseId = $quiz->course_id;
        $segmentId = $quiz->segment_id;
        $currentTime = now();

        // Get total lessons for the specific course and segment
        $totalLessons = Lesson::where('course_id', $courseId)
                        ->where('segments_id', $segmentId)
                        ->count();

        // Get the count of lessons attempted by the student
        $attemptedLessons = ProgressAll::where('student_id', $studentId)
                                ->where('course_id', $courseId)
                                ->where('segments_id', $segmentId)
                                ->count();

        // Check if the student has completed all lessons in the segment
        if ($attemptedLessons < $totalLessons) {
            return response()->json(['error' => 'Please complete all lessons in this segment before attempting the quiz.'], 403);
        }

        // Retrieve student's progress record
        $progress = Progress::where('student_id', $studentId)
                            ->where('course_id', $courseId)
                            ->where('segments_id', $segmentId)
                            ->first();

        // Define quiz attempt limits
        $attemptLimit = 3;
        $attemptCooldown = 2; // cooldown in hours

        // Check if the student has reached the maximum number of attempts
        if ($progress->quiz_attempt >= $attemptLimit) {
            $lastAttemptTime = Carbon::parse($progress->last_attempt_time);
            if ($lastAttemptTime->diffInHours($currentTime) < $attemptCooldown) {
                return response()->json(['error' => 'You must wait 2 hours to retake this quiz.'], 403);
            } else {
                $progress->quiz_attempt = 0;
            }
        }

        // Set up for a new attempt period
        if ($progress->quiz_attempt == 0) {
            $progress->quiz_attempt = 1; // Increment for the current attempt
            $progress->last_attempt_time = $currentTime;
            $progress->score = 0;
            $progress->save();
        }

        // Retrieve quiz questions if all checks are passed
        $questions = Question::where('quiz_id', $quizId)
            ->get(['id', 'content', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer']);

        return response()->json($questions);
    }

    public function saveAnswer(Request $request)
    {
        try {            
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
    

    public function finishQuiz(Request $request, $quizId)
    {
        $studentId = $request->input('student_id');
        $courseId = $request->input('course_id');
        $segmentId = $request->input('segment_id');
        $score = $request->input('score');
        $passMark = $request->input('pass_mark');
        
        // Find or create quiz record for the student
        $quizRecord = Progress::firstOrCreate(
            ['student_id' => $studentId, 'course_id' => $courseId, 'segments_id' => $segmentId],
            ['quiz_attempt' => 0, 'completed' => 0, 'last_attempt_time' => null, 'score' => 0] // Default values
        );

        // Check if the score is not less than the pass mark
        if ($score >= $passMark) {
            $quizRecord->completed = 1;
            $quizRecord->progress_percentage = 100;
        } else {
            // If score is less than pass mark, check attempts
            if ($quizRecord->quiz_attempt >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only try again after 24 hours.'
                ]);
            }
        }

        // Increment quiz attempts and update last attempt time
        $quizRecord->quiz_attempt += 1;
        $quizRecord->last_attempt_time = now();
        $quizRecord->score = $score;

        // Save the quiz record
        $quizRecord->save();

        return response()->json(['success' => true]);
    }

}
