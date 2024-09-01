<?php

namespace App\Livewire;

use App\Models\Exam;
use Livewire\Component;
use App\Models\Ranking;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use App\ExamStatisticsTrait;


class ExamRanking extends Component
{
    use ExamStatisticsTrait;

    public Exam $exam;
    public $userRankings = [];
    public bool $userAnsweredAllQuestions;

    public $count = 0;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }

    // public function mount(Exam $exam): void
    // {
    //     $this->exam = $exam;

    //     $user = Auth::user();
    //     $totalQuestions = $this->exam->examQuestions->count();

    //     $markedAlternatives = $this->getMarkedAlternatives($user, $this->exam);
    //     $this->userAnsweredAllQuestions = $markedAlternatives->count() === $totalQuestions;
    // }

    // public function loadUserRankings()
    // {
    //     // Carrega o ranking já calculado do banco de dados
    //     $this->userRankings = $this->exam->rankings()->orderBy('position')->get()->collect();
    // }

    // #[On('rankingUpdated')] // Escuta o evento de atualização do ranking
    // public function handleRankingUpdated()
    // {
    //     $this->loadUserRankings(); // Recarrega o ranking quando o evento é recebido
    // }

    public function render()
    {
        return view('livewire.exam-ranking');
    }
}
