<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\Ranking;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use App\ExamStatisticsTrait;

class ExamRanking extends Component
{
    use ExamStatisticsTrait;

    public $examId;
    public Exam $exam;
    public $userRankings = [];
    public bool $userAnsweredAllQuestions;
    public bool $isUpdating = false;
    public $userPosition;
    public $userCorrectAnswers;
    public $userPercentage;

    public function mount($examId)
    {
        $this->examId = $examId;
        $this->exam = Exam::findOrFail($examId);
        $this->loadUserRankings();
    }

    #[On('ranking.updated')]
    public function refreshComponent()
    {
        Log::info('Forçando atualização do componente');
        $this->dispatch('$refresh');
    }

    public function loadUserRankings()
    {
        $this->isUpdating = true;
        $this->userRankings = $this->exam->rankings()->orderBy('position')->get();

        $this->calculateUserStats();

        if (!$this->userRankings->isEmpty()) {
            $this->dispatch('$refresh');
        }

        $this->isUpdating = false;
    }

    protected function calculateUserStats()
    {
        $userId = auth()->id();
        $totalQuestions = $this->exam->examQuestions()->count();

        foreach ($this->userRankings as $index => $ranking) {
            if ($ranking->user_id == $userId) {
                $this->userPosition = $index + 1;
                $this->userCorrectAnswers = $ranking->correct_answers;
                $this->userPercentage = ($ranking->correct_answers / $totalQuestions) * 100;
                break;
            }
        }
    }

    public function render()
    {
        return view('livewire.exam-ranking', [
            'userRankings' => $this->userRankings,
            'isUpdating' => $this->isUpdating,
            'userPosition' => $this->userPosition,
            'userCorrectAnswers' => $this->userCorrectAnswers,
            'userPercentage' => $this->userPercentage,
        ]);
    }
}
