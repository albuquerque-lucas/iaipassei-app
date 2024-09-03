<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Support\Facades\Auth;
use App\ExamStatisticsTrait;
use Livewire\Component;

class ExamAnswerForm extends Component
{
    use ExamStatisticsTrait;  // Usando a trait ExamStatisticsTrait

    public $examId;
    public $exam;
    public $questions = [];
    public $markedAlternatives = [];
    public bool $isSubmitting = false;

    public function mount($examId)
    {
        $this->examId = $examId;
        $this->loadExamData();
    }

    public function loadExamData()
    {
        $this->exam = Exam::findOrFail($this->examId);
        $this->questions = ExamQuestion::where('exam_id', $this->exam->id)->get();

        $user = Auth::user();
        $marked = $user->markedAlternatives()
            ->whereIn('user_question_alternatives.exam_question_id', $this->questions->pluck('id'))
            ->withPivot('exam_question_id')
            ->get()
            ->keyBy('pivot.exam_question_id');

        foreach ($this->questions as $question) {
            $this->markedAlternatives[$question->id] = $marked[$question->id]->letter ?? '';
        }
    }

    public function submit()
    {
        $this->isSubmitting = true;
        $user = Auth::user();

        foreach ($this->questions as $question) {
            $selectedAlternativeLetter = $this->markedAlternatives[$question->id] ?? null;

            if ($selectedAlternativeLetter) {
                $selectedAlternative = $question->alternatives()->where('letter', $selectedAlternativeLetter)->first();

                if ($selectedAlternative) {
                    $user->markedAlternatives()
                        ->wherePivot('exam_question_id', $question->id)
                        ->detach();

                    $user->markedAlternatives()->attach($selectedAlternative->id, ['exam_question_id' => $question->id]);
                }
            }
        }

        // Calcular e salvar o ranking diretamente
        $this->calculateAndSaveRanking();

        $this->isSubmitting = false;
        session()->flash('success', 'As respostas foram enviadas com sucesso. O ranking será atualizado automaticamente em instantes.');
        return redirect()->route('public.exams.results', ['exam' => $this->exam->slug]);
    }

    private function calculateAndSaveRanking()
    {
        $rankings = $this->calculateUserRankings($this->exam->id);

        foreach ($rankings as $position => $ranking) {
            $this->exam->rankings()->updateOrCreate(
                ['user_id' => $ranking['user']->id],
                [
                    'position' => $position + 1,
                    'correct_answers' => $ranking['correct_answers'],
                    'exam_id' => $this->exam->id,
                ]
            );
        }
    }

    public function render()
    {
        return view('livewire.exam-answer-form', [
            'exam' => $this->exam,
            'questions' => $this->questions,
            'markedAlternatives' => $this->markedAlternatives,
            'isSubmitting' => $this->isSubmitting,
        ]);
    }
}
