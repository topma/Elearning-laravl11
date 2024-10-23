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
}
