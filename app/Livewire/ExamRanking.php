<?php

namespace App\Livewire;

use App\Models\Exam;
use Livewire\Component;
use App\Models\Ranking;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class ExamRanking extends Component
{
    public $examId;
    public Exam $exam;
    public $userRankings = [];
    public bool $userAnsweredAllQuestions;
    public bool $isUpdating = false;

    public function mount($examId)
    {
        $this->examId = $examId;
        $this->exam = Exam::findOrFail($examId);
        $this->loadUserRankings();
    }

    #[On('ranking.updated')]
    public function loadUserRankings()
    {
        Log::info('Identificando evento e carregando rankings');
        $this->isUpdating = true; // Exibe o indicador antes de carregar os dados
        usleep(50000);
        $this->userRankings = $this->exam->rankings()->orderBy('position')->get();
        $this->isUpdating = false; // Oculta o indicador após carregar os dados
    }

    public function render()
    {
        Log::info('Renderizando o componente ExamRanking');
        return view('livewire.exam-ranking', [
            'userRankings' => $this->userRankings,
            'isUpdating' => $this->isUpdating,
        ]);
    }
}
