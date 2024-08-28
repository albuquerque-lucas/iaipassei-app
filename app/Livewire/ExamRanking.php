<?php

namespace App\Livewire;

use App\Models\Exam;
use App\ExamStatisticsTrait;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ExamRanking extends Component
{
    use ExamStatisticsTrait;

    public Exam $exam;
    public $userRankings = [];
    public bool $userAnsweredAllQuestions;

    public function mount(Exam $exam): void
    {
        $this->exam = $exam;

        $user = Auth::user();
        $totalQuestions = $this->exam->examQuestions->count();

        $markedAlternatives = $this->getMarkedAlternatives($user, $this->exam);
        $this->userAnsweredAllQuestions = $markedAlternatives->count() === $totalQuestions;

        // Inicialmente, os rankings são vazios e são carregados quando a página termina de carregar.
    }

    public function loadUserRankings()
    {
        // Este método carrega os rankings dos usuários
        $this->userRankings = $this->calculateUserRankings($this->exam->id);
    }

    public function render()
    {
        return view('livewire.exam-ranking', [
            'userRankings' => $this->userRankings,
            'userAnsweredAllQuestions' => $this->userAnsweredAllQuestions,
            'exam' => $this->exam,
        ]);
    }
}
