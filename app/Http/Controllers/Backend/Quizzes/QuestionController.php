<?php

namespace App\Http\Controllers\Backend\Quizzes; 

use App\Models\Question;
use App\Models\Segments;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use Exception;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() 
    {
        $question = Question::paginate(10);
        return view('backend.quiz.question.index', compact('question'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {        
        $quiz = Quiz::get();
        return view('backend.quiz.question.create', compact('quiz'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createQuestion($id)
    {
        $decryptedId = encryptor('decrypt', $id);

        $quiz = Quiz::where('id', $decryptedId)->first();
        $segment = Segments::find($quiz->segment_id); 
        return view('backend.quiz.question.create', compact('quiz','segment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $question = new Question;
            $question->quiz_id = $request->quizId;
            $question->type = $request->questionType;
            $question->content = $request->questionContent;
            $question->option_a = $request->optionA;
            $question->option_b = $request->optionB;
            $question->option_c = $request->optionC;
            $question->option_d = $request->optionD;
            $question->correct_answer = $request->correctAnswer;
            $question->course_id = $request->courseId;
            $question->segment_id = $request->segmentId;

            if ($question->save()) {
                $this->notice::success('Data Saved');
                return redirect()->route('question.show', encryptor('encrypt', $request->quizId));
            } else {
                $this->notice::error('Please try again');
                return redirect()->back()->withInput();
            }
        } catch (Exception $e) {
            dd($e);
            $this->notice::error('Please try again');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $decryptedId = encryptor('decrypt', $id);

        // Find the quiz by its ID
        $quiz = Quiz::findOrFail($decryptedId);

        // Get the segment title using the segment_id from the quiz
        $segment = Segments::find($quiz->segment_id); 

        // Retrieve questions associated with the quiz
        $question = Question::where('quiz_id', $quiz->id)->paginate(10);

        // Pass the segment title to the view
        return view('backend.quiz.question.view', compact('question', 'quiz', 'segment'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $decryptedId = encryptor('decrypt', $id);
       
        $question = Question::findOrFail($decryptedId);
        $quiz = Quiz::where('id', $question->quiz_id)->first();
        $segment = Segments::find($quiz->segment_id); 
        return view('backend.quiz.question.edit', compact('quiz', 'question','segment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $question = Question::findOrFail(encryptor('decrypt', $id));
            $question->quiz_id = $request->quizId;
            $question->type = $request->questionType;
            $question->content = $request->questionContent;
            $question->option_a = $request->optionA;
            $question->option_b = $request->optionB;
            $question->option_c = $request->optionC;
            $question->option_d = $request->optionD;
            $question->correct_answer = $request->correctAnswer;
            

            if ($question->save()) {
                $this->notice::success('Data Saved');
                return redirect()->route('question.show', encryptor('encrypt', $request->quizId));
            } else {
                $this->notice::error('Please try again');
                return redirect()->back()->withInput();
            }
        } catch (Exception $e) {
            dd($e);
            $this->notice::error('Please try again');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Question::findOrFail(encryptor('decrypt', $id));
        if ($data->delete()) {
            $this->notice::error('Data Deleted!');
            return redirect()->back();
        }
    }
}
