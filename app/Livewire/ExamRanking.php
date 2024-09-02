<?php

namespace App\Livewire;

use App\Models\Exam;
use Livewire\Component;
use App\Models\Ranking;

class ExamRanking extends Component
{
    public Exam $exam;
    public $userRankings = [];
    public bool $userAnsweredAllQuestions;
    public bool $isUpdating = false; // Variável de estado para o indicador visual

    public function mount(Exam $exam)
    {
        $this->exam = $exam;
        $this->loadUserRankings();
    }

    public function loadUserRankings()
    {
        $this->isUpdating = true; // Exibe o indicador antes de carregar os dados
        $this->userRankings = $this->exam->rankings()->orderBy('position')->get();
        $this->isUpdating = false; // Oculta o indicador após carregar os dados
    }

    protected $listeners = [
        'echo:exam-ranking-updated,ranking.updated' => 'loadUserRankings',
    ];

    public function render()
    {
        return view('livewire.exam-ranking', [
            'userRankings' => $this->userRankings,
            'isUpdating' => $this->isUpdating, // Passa a variável para a view
        ]);
    }
}
