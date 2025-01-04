<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SupportQuestion;

class QuestionsController extends Controller
{
    public function getFrequentQuestions()
    {
        $frequentQuestions = SupportQuestion::where('is_frequent', true)->get(['id', 'question', 'answer']);
        return response()->json($frequentQuestions);
    }

    public function submitQuestion(Request $request)
    {
      try{
            $request->validate([
                'question' => 'required|string|max:500',
            ]);

            $user = $request->user();

            $question = SupportQuestion::create([
                'user_id' => $user->id,
                'question' => $request->question,
                'status' => 'pending',
            ]);

            return response()->json([
                'message' => 'Your question has been submitted successfully.',
                'question' => $question,
            ],201);
        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(), // Returns an array of errors for each field
            ], 422); // 422 Unprocessable Entity
        }
    }
    public function getUserQuestions(Request $request)
    {
        $user = $request->user();

        $userQuestions = SupportQuestion::where('user_id', $user->id)
            ->get(['id', 'question', 'answer', 'status']);

        return response()->json($userQuestions);
    }


    
//for employee
//for employee
//for employee
//for employee
//for employee
//for employee
//for employee
//for employee
//for employee
//for employee
//for employee

    public function getAllQuestions()
    {
        $questions = SupportQuestion::
              orderBy('status', 'asc') 
            ->orderBy('created_at', 'asc') 
            ->get();

        return response()->json([
            'message' => 'Questions retrieved successfully',
            'questions' => $questions,
        ], 201);
    }

    public function markAsFrequent($id)
    {
        $question = SupportQuestion::find($id);

        if (!$question) {
            return response()->json([
                'message' => 'Question not found',
            ], 404);
        }

        $question->is_frequent = true;
        $question->save();

        return response()->json([
            'message' => 'Question marked as frequent successfully',
            'question' => $question,
        ], 201);
    }
    public function removeFromFrequent($id)
    {
        $question = SupportQuestion::find($id);
    
        if (!$question) {
            return response()->json([
                'message' => 'Question not found',
            ], 404);
        }
    
        $question->is_frequent = false;
        $question->save();
    
        return response()->json([
            'message' => 'Question removed from frequent list successfully',
            'question' => $question,
        ], 201);
    }
    public function getQuestionDetails($id)
    {
        $question = SupportQuestion::with('user') 
            ->find($id);
    
        if (!$question) {
            return response()->json([
                'message' => 'Question not found',
            ], 404);
        }
    
        return response()->json([
            'message' => 'Question details retrieved successfully',
            'question' => $question,
        ], 201);
    }
    public function answerQuestion(Request $request, $id)
    {
        $validated = $request->validate([
            'answer' => 'nullable|string',
        ]);
    
        $question = SupportQuestion::find($id);
    
        if (!$question) {
            return response()->json([
                'message' => 'Question not found',
            ], 404);
        }
    
        $question->answer = $validated['answer'];
        $question->status = 'answered'; 
        $question->save();
    
        return response()->json([
            'message' => 'Answer saved successfully',
            'question' => $question,
        ], 201);
    }
    




}
