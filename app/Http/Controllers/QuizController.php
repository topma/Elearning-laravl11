<?php

namespace App\Http\Controllers;
use App\Models\Question;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    //

    public function getQuestions($quizId)
    {
        $questions = Question::where('quiz_id', $quizId)->get(['id', 'content', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer']);
        return response()->json($questions);
    }
}
