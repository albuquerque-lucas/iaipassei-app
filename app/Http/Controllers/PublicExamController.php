<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamQuestion;

class PublicExamController extends Controller
{
    public function show($slug) {
        $exam = Exam::where('slug', $slug)->first();
        $questions = ExamQuestion::where('exam_id', $exam->id)->paginate(5);
        $title= $exam->title;
        return view('public.exams.show', compact('exam', 'questions', 'title'));
    }
}
